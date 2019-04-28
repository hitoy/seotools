#!/usr/bin/env python
import urllib.request,re,sys,time

pushurl = "http://data.zz.baidu.com/urls?site=zh.camelway.com&token=Xem54Yt3FsXzAEJ4"

try:
    sitemap = sys.argv[1]
except BaseException as e:
    print('Must Input Your Sitemap!')
    sys.exit(-1)

while True:
    urls = urllib.request.urlopen(sitemap).read().decode('utf-8')
    urls = re.sub("<[^>]*>", "\r\n", urls)
    urls = re.findall(r'(http.*?)[\r\n]', urls)
    urls = "\r\n".join(urls)
    res = urllib.request.Request(pushurl, headers = {'Content-Type': 'text/plain', 'Content-Length': len(urls)}, data = bytes(urls, 'utf8'))
    result = urllib.request.urlopen(res).read().decode('utf-8')
    print(result)

    time.sleep(3600)
