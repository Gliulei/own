#!/usr/bin/env python
# -*- coding: utf-8 -*-

import json
from mitmproxy import http
import requests

def response(flow: http.HTTPFlow):
	# ly 日志处理
	ly_log_filter(flow)

def url2Dict(url, urlparse=None):
	query = urlparse.urlparse(url).query
	return dict([(k, v[0]) for k, v in urlparse.parse_qs(query).items()])

# 联运日志处理
def ly_log_filter(flow):
	status_code = flow.response.status_code

	ret = flow.response.content.decode('utf-8', 'ignore')

	try:
		path = flow.request.path_components
		if len(path) == 4:
			url = flow.request.query
			print(url['user_id'])
			ret = json.loads(ret)
			aweme_lists = ret['aweme_list']
			for aweme_list in aweme_lists:
				video_url = aweme_list['video']['play_addr_lowbr']['url_list'][1]
				video_id = aweme_list['video']['play_addr']['uri']
				save_file(video_url,video_id, aweme_list['desc'])

	except Exception as e:
		print(e)
# 保存图片到磁盘文件夹 file_path中
def save_file(download_source_file,dy_id, desc):
	requests.get('',params={'video_id':dy_id,'url':download_source_file, 'desc':desc})
	return True