//const url = 'server/upload.php';    // 后台URL

/**
 * WebUploader 配置
 */
//let userInfo = {userId: "up_file", md5: ""};   //用户会话信息
//let uniqueFileName = null;          //文件唯一标识符
//let md5Mark = null;

//const chunkSize = 2 * 1024 * 1024;        //分块大小

WebUploader.Uploader.register({
    "before-send-file": "beforeSendFile"
    , "before-send": "beforeSend"
    , "after-send-file": "afterSendFile"
}, {
    beforeSendFile: function (file) {
        //秒传验证
        let task = new $.Deferred();
        let start = new Date().getTime();

        (new WebUploader.Uploader())
            .md5File(file, 0, 10 * 1024 * 1024)
            .progress(function (percentage) {
                console.log('progress: ' + percentage);
            })
            .then(function (val) {
                console.log("总耗时: " + ((new Date().getTime()) - start) / 1000);

                md5Mark = val;
                userInfo.md5 = val;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        act: "chunk",
                        status: "md5Check",
                        md5: val,
                    },
                    cache: false,
                    timeout: 1000, //todo 超时的话，只能认为该文件不曾上传过
                    dataType: "json",
                    success: function (data) {
                        if (data.ifExist) {   //若存在，这返回失败给WebUploader，表明该文件不需要上传
                            task.reject();

                            uploader.skipFile(file);
                            file.path = data.path;
                            UploadComplete(file, data);
                        } else {
                            task.resolve();
                            //拿到上传文件的唯一名称，用于断点续传
                            uniqueFileName = md5(userInfo.userId + file.name + file.type + file.lastModifiedDate + file.size);
                        }
                    },
                    error: function () {
                        task.resolve();
                        //拿到上传文件的唯一名称，用于断点续传
                        uniqueFileName = md5(userInfo.userId + file.name + file.type + file.lastModifiedDate + file.size);
                    }
                });
            });
        return $.when(task);
    },
    beforeSend: function (block) {
        //分片验证是否已传过，用于断点续传
        let task = new $.Deferred();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                act: "chunk",
                status: "chunkCheck",
                name: uniqueFileName,
                chunkIndex: block.chunk,
                size: block.end - block.start,
            },
            cache: false,
            timeout: 1000, //todo 超时的话，只能认为该分片未上传过
            dataType: "json",
            success: function (data) {
                if (data.ifExist) {   // 若存在，返回失败给WebUploader，表明该分块不需要上传
                    task.reject();
                } else {
                    task.resolve();
                }
            },
            error: function () {    // 任何形式的验证失败，都触发重新上传
                task.resolve();
            }
        });

        return $.when(task);
    },
    afterSendFile: function (file) {
        let chunksTotal = Math.ceil(file.size / chunkSize);
        if (chunksTotal > 1) {
            //合并请求
            let task = new $.Deferred();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    act: "chunk",
                    status: "chunksMerge",
                    name: uniqueFileName,
                    chunks: chunksTotal,
                    ext: file.ext,
                    md5: md5Mark,
                },
                cache: false,
                dataType: "json",
                success: function (data) {
                    //todo 检查响应是否正常
                    console.log('afterSendFile-success:', data);
                    task.resolve();
                    file.path = data.path;

                    UploadComplete(file, data);
                },
                error: function (errors) {
                    console.log('afterSendFile-error', errors.responseText);
                    task.reject();
                }
            });

            return $.when(task);
        } else {    // 上传文件小于最小分片值： `chunkSize`
            UploadComplete(file);
        }
    }
});

/**
 * 创建 WebUploader 实例
 * @type {any | c}
 */
let uploader = WebUploader.create({
    swf: "Uploader.swf",
    server: url,
    pick: "#picker",
    resize: false,
    dnd: "#theList",
    paste: document.body,
    disableGlobalDnd: true,
    thumb: {
        width: 100,
        height: 100,
        quality: 70,
        allowMagnify: true,
        crop: true,
        //, type: "image/jpeg"
    },
    /*, compress: {
     quality: 90
     , allowMagnify: false
     , crop: false
     , preserveHeaders: true
     , noCompressIfLarger: true
     ,compressSize: 100000
     },*/
    compress: false,
    prepareNextFile: true,
    chunked: true,
    chunkSize: chunkSize,
    formData: userInfo,
    method: 'POST',
    threads: 1,
    fileNumLimit: 1,
    fileSingleSizeLimit: maxSize,
    duplicate: true
});

uploader.on("fileQueued", function (file) {
    $("#theList").append(
        '<li id="' + file.id + '">' +
        '<img />' +
        '<span>' + file.name + '</span>' +
        '<span class="itemUpload">上传</span>' +
        '<span class="itemStop">暂停</span>' +
        '<span class="itemDel">删除</span>' +
        '<div class="percentage"></div>' +
        '</li>'
    );

    let $img = $("#" + file.id).find("img");

    uploader.makeThumb(file, function (error, src) {
        if (error) {
            $img.replaceWith("<span>不能预览</span>");
        }
        $img.attr("src", src);
    });
});

uploader.on("uploadProgress", function (file, percentage) {
    $("#" + file.id + " .percentage").text(percentage * 100 + "%");
});

/**
 * 分片上传完整整合
 * @param file
 * @param data
 * @constructor
 */
function UploadComplete(file, data = '') {
    console.log(file, data);
    if (file && data && data.type == 1) {
        $.ajax({
            url: url,
            type: "POST",
            data: {
                act: "upload",
                name: file.name,
                type: file.type,
                tmp_name: data.path,
                size: file.size,
            },
            dataType: "json",
            cache: false,
            success: function (data) {
                if (data.code == 1) {
                    /* $("#app_url").val(data.url);
                    $("#app_size").val(data.size); */

                    $("#" + file.id + " .percentage").text("上传完毕").css({'color': 'red'});
                    $(".itemStop").hide();
                    $(".itemUpload").hide();
                    $(".itemDel").hide();
                }
                if (data.code == 2) {
                    $("#" + file.id + " .percentage").text(data.mes).css({'color': 'red'});
                }
            },
            error: function () {
                $("#" + file.id + " .percentage").text('网络错误！').css({'color': 'red'});
            }
        });
    }
}

/**
 * 开始上传
 */
$("#theList").on("click", ".itemUpload", function () {
    uploader.upload();
    //"上传"-->"暂停"
    $(this).hide();
    $(".itemStop").show();
});

/**
 * 暂停上传
 */
$("#theList").on("click", ".itemStop", function () {
    uploader.stop(true);
    //"暂停"-->"上传"
    $(this).hide();
    $(".itemUpload").show();
});

/**
 * 删除上传
 */
$("#theList").on("click", ".itemDel", function () {
    //todo 如果要删除的文件正在上传（包括暂停），则需要发送给后端一个请求用来清除服务器端的缓存文件

    uploader.removeFile($(this).parent().attr("id"));   //从上传文件列表中删除
    $(this).parent().remove();  //从上传列表dom中删除
});
