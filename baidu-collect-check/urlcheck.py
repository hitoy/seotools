#!/usr/bin/env python
# -*-coding:utf-8 -*-
# Baidu Url Check Tool
# Author Hito Yang
print u"百度收录检查工具 By Hito\r\n"
import httplib,urllib,sys,gzip,StringIO,re,time
jumpre = re.compile(r"http:\/\/www.baidu.com\/link\?url\=[^\"]*")
urlre = re.compile(r"URL\=\'([^\'\"]*)")
urire = re.compile(r"\.com(.*)$")
def debug(txt):
    log = open("log.txt","w+")
    log.write(txt)
    log.close()
def GetResponse(url,referer="https://www.baidu.com/",ua="Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"):
    uri = urire.findall(url)
    header = {"Host":"www.baidu.com","Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Encoding":"gzip, identity","Accept-Language":"zh-CN,zh;q=0.8","Cache-Control":"max-age=0","Connection":"keep-alive","User-Agent":ua}
    try:
        client = httplib.HTTPConnection("www.baidu.com",80,timeout=10)
        client.request("GET",uri[0],headers=header)
        res =  client.getresponse()
    except:
        res = None
    if res:
        headers = dict(res.getheaders())
        if 'content-encoding' in headers:
            body = gz_decoding(res.read())
        else:
            body = res.read()
        return headers,body
    else:
        return ()

def gz_decoding(data):
    compressedstream = StringIO.StringIO(data)  
    gziper = gzip.GzipFile(fileobj=compressedstream)    
    data2 = gziper.read() 
    return data2


def get_jump(httpdata):
    tmp=list()
    match = jumpre.findall(httpdata)
    for i in match:
        if not i in tmp:
            tmp.append(i)
    return tmp

def is_collected(targeturl,urllist):
    for url in urllist:
        header,body = GetResponse(url)
        jump = None
        if(header['location']):
            jump = header['location'].lstrip("http://").rstrip("/")
        else:
            jump = urlre.findall(body).lstrip("http://").rstrip("/")
        if targeturl.lstrip("http://").rstrip("/") == jump:
            return True
    return False


arg = sys.argv
if len(arg) == 1:
    sys.stdout.write(u"请输入需要查询的URL或者存放URL的文件!\n")
    exit()
else:
    key = arg[1].strip()

if re.match(r"^http",key):
    urls = [key]
else:
    try:
        f = open(key,"rb")
        urls = f.readlines()
        f.close()
    except Exception,e:
        sys.stdout.write("%s\n"%e)

for url in urls:
    try:
        time.sleep(5)
    except KeyboardInterrupt:
        sys.stdout.write(u"Ctrl+C结束\n")
        break
    url=url.strip()
    if len(url)==0:continue
    url = urllib.quote(url)
    baidu = GetResponse("http://www.baidu.com/s?wd=%s"%url)
    jumplist = get_jump(baidu[1])
    url = urllib.unquote(url)
    if "很抱歉，没有找到与" in baidu[1]:
        sys.stdout.write(u"网址:%s 未收录!\n"%url)
        continue
    if len(jumplist) == 0:
        sys.stdout.write(u"网址:%s 未知 百度阻止了此次查询,请稍后再试\n"%url)
        if "debug" in arg:
            txt = str(baidu[0])+baidu[1]
            debug(txt)
        continue
    if(is_collected(url,jumplist)):
        sys.stdout.write(u"网址:%s 被收录!\n"%url)
        continue
    else:
        sys.stdout.write(u"网址:%s 未收录!\n"%url)
        continue
