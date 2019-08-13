<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>大文件断点续传</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('bigfile/css/webuploader.css')}}"/>
    <style>
        .itemDel, .itemStop, .itemUpload {
            margin-left: 15px;
            color: blue;
            cursor: pointer;
        }

        #theList {
            width: 80%;
            min-height: 100px;
            border: 1px solid red;
        }

        #theList .itemStop {
            display: none;
        }
    </style>
</head>
<body>


<div id="uploader">
    <ul id="theList"></ul>
    <div id="picker">选择文件</div>
</div>

<script type="text/javascript" src="{{asset('bigfile/js/jquery.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" src="{{asset('bigfile/js/webuploader.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" src="{{asset('bigfile/js/md5.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    /**
     * WebUploader 配置
     */
    const url = '{{route('bigfile_upload')}}'
    const chunkSize = '{{$data['chunk_size']}}'
    const maxSize = '{{$data['max_size']}}'

    let user_id = @if($data['user_id']){{$data['user_id']}}@else 'up_file' @endif;
    let userInfo = { userId: user_id, md5: '' }   //用户会话信息
    let uniqueFileName = null          //文件唯一标识符
    let md5Mark = null
</script>
<script type="text/javascript" src="{{asset('bigfile/js/bigFileUploader.js')}}" charset="utf-8"></script>
</body>
</html>
