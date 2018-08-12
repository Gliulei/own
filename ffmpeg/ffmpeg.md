####视频转码
ffmpeg -i 侯明昊第一条.mov -s hd720 -c:v libx264 -crf 23 -c:a aac -strict -2 -pix_fmt yuv420p 侯明昊.mp4

####查看视频信息
ffprobe 侯明昊.mp4

#####文章
http://www.cnblogs.com/dwdxdy/p/3240167.html