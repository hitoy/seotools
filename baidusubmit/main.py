#!/usr/bin/env python
import urllib.request,re,sys,time
URLLIST = list()

pushurl = "http://data.zz.baidu.com/urls?site=zh.camelway.com&token=Xem54Yt3FsXzAEJ4"
xiongzhangpushurl = "http://data.zz.baidu.com/urls?appid=1630841799366655&token=Gmlp2vwKtKZBtBjQ&type=realtime"
xiongzhangweekurl = "http://data.zz.baidu.com/urls?appid=1630841799366655&token=Gmlp2vwKtKZBtBjQ&type=batch"

try:
    sitemap = sys.argv[1]
except BaseException as e:
    print('Must Input Your Sitemap!')
    sys.exit(-1)
try:
    interval = int(sys.argv[2])
except BaseException:
    interval = 10

while True:
    try:
        nocachesitemap = "%s?page=%s"%(sitemap, time.time())
        urls = urllib.request.urlopen(nocachesitemap).read().decode('utf-8')
        urls = re.sub("<[^>]*>", "\r\n", urls)
        urls = re.findall(r'(http.*?)[\r\n]', urls)
        newurls = list(set(urls).difference(set(URLLIST)))
        URLLIST.extend(newurls)

        print('%s, Success to Get Sitemap %s'%(time.ctime(), nocachesitemap))
        print('Total %d url(s) Founded, %d new url(s)'%(len(urls), len(newurls)))

        if(len(newurls) > 0):
            xurls = "\r\n".join(newurls)
#百度站长URL提交，无限制
            res = urllib.request.Request(pushurl, headers = {'Content-Type': 'text/plain', 'Content-Length': len(xurls)}, data = bytes(xurls, 'utf8'))
            result = urllib.request.urlopen(res).read().decode('utf-8')
            print("Ping Baidu ZhanZhang: %s"%result)
#熊掌号提交
            res = urllib.request.Request(xiongzhangpushurl, headers = {'Content-Type': 'text/plain', 'Content-Length': len(xurls)}, data = bytes(xurls, 'utf8'))
            result = urllib.request.urlopen(res).read().decode('utf-8')
            print("Ping Baidu XiongZhang: %s"%result)
#熊掌周级收录
            res = urllib.request.Request(xiongzhangweekurl, headers = {'Content-Type': 'text/plain', 'Content-Length': len(xurls)}, data = bytes(xurls, 'utf8'))
            result = urllib.request.urlopen(res).read().decode('utf-8')
            print("Ping Baidu XiongZhang Week: %s"%result)

        print("\n");
    except KeyboardInterrupt as e:
        break

    except BaseException as e:
        print(e)

    time.sleep(interval)
