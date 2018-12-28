####前言
最近因为用户上传一些视频在app上播放不了,不是因为格式不对,就是编码不对。从网上谷歌了下找到了FFmpeg这个命令,从而实现自动化。
FFmpeg是一套可以用来记录、转换数字音频、视频，并能将其转化为流的开源计算机程序。采用LGPL或GPL许可证。它提供了录制、转换以及流化音视频的完整解决方案,
可用于视频转码,分离视频音频流,视频剪切,最主要是能够自动化,多线程。


####安装
MAC 用brew直接安装
```
brew install FFmpeg
```

linux 这里采用yum安装
```
sudo yum install ffmpeg ffmpeg-devel -y
```

测试是否安装成功
```
ffmpeg
```

####常用基本命令
1.分离视频音频流
```
ffmpeg -i input_file -vcodec copy -an output_file_video  //分离视频流
ffmpeg -i input_file -acodec copy -vn output_file_audio  //分离音频流
```
2.视频转码
```
ffmpeg -threads 16 -i test.mov -s hd720 -c:v libx264 -crf 23 -c:a aac -strict -2 -pix_fmt yuv420p test.mp4
//-threads 使用几个线程，-crf 画质参数 参数的取值范围为0~51，其中0为无损模式，数值越大，画质越差，生成的文件却越小
```
4.视频封装
```
ffmpeg –i video_file –i audio_file –vcodec copy –acodec copy output_file
```
5.视频剪切
```
ffmpeg –i test.avi –r 1 –f image2 image-%3d.jpeg        //提取图片
ffmpeg -ss 0:1:30 -t 0:0:20 -i input.avi -vcodec copy -acodec copy output.avi    //剪切视频
//-r 提取图像的频率，-ss 开始时间，-t 持续时间
```
6.视频录制
```
ffmpeg –i rtsp://192.168.3.205:5555/test –vcodec copy out.avi
```
7.YUV序列播放
```
ffplay -f rawvideo -video_size 1920x1080 input.yuv
```
8.YUV序列转AVI
```
ffmpeg –s w*h –pix_fmt yuv420p –i input.yuv –vcodec mpeg4 output.avi
```

####常用参数说明：

主要参数：
-threads 设置线程数
-i 设定输入流
-f 设定输出格式
-ss 开始时间
视频参数：
-b 设定视频流量，默认为200Kbit/s
-r 设定帧速率，默认为25
-s 设定画面的宽与高
-aspect 设定画面的比例
-vn 不处理视频
-vcodec 设定视频编解码器，未设定时则使用与输入流相同的编解码器
音频参数：
-ar 设定采样率
-ac 设定声音的Channel数
-acodec 设定声音编解码器，未设定时则使用与输入流相同的编解码器
-an 不处理音频


####查看视频信息
ffprobe test.mp4

#####文章
http://www.cnblogs.com/dwdxdy/p/3240167.html