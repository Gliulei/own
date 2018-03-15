awk 'BEGIN{FS="\t"} {if ($15>1) print $12 $15}' access.log 找耗时大于1秒的接口

awk '{print $1}' access.log | sort | uniq -c | sort -rn 访问ip统计

https://www.bo56.com/%E8%B0%83%E8%AF%95%E5%88%A9%E5%99%A8%E4%B9%8Btcpdump/