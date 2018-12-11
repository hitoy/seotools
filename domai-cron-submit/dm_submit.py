#!/usr/bin/env python3
# -*- coding:utf-8 -*-
import argparse,urllib.request,urllib.parse,re,json,os

GoogleIndexAPI = 'https://indexing.googleapis.com/v3/urlNotifications:publish'
UserAgent = 'Mozilla/5.0 (Domai Spider/0.1 https://www.hitoy.org/)'
replacere = re.compile(r"^(.*)->(.*)$",re.I|re.S)

status = dict()

parser = argparse.ArgumentParser(description="Domai CMS与Domai Meta Search配套定时采集和发布工具\r\n系统必须满足多个条件才能使用，请联系vip@hitoy.org",formatter_class=argparse.RawTextHelpFormatter)
parser.add_argument('-k',dest='keyfile',type=str,help='指定关键词文件，默认为key.txt',default='key.txt')
parser.add_argument('-d',dest='daily',type=int,help='指定每个网站每天的发布量，默认为20',default=20)
parser.add_argument('-i',dest='interval',type=str,help='每个网站关键词的发布间隔,单位秒，默认为自动计算',default='auto')
parser.add_argument('-m',dest='model',type=str,help='发布模式，repeat代表一个关键词发布多个网站，cycle代表一个关键词只发布一个网站，默认为cycle模式',default='cycle')
parser.add_argument('-o',dest='origin',type=str,help='指定采集源地址')
parser.add_argument('-q',dest='query',type=str,help='指定查询关键字，默认为q',default='q')
parser.add_argument('-w',dest='website',type=str,help='指定发布的地址，多个用空格分隔')
parser.add_argument('-p',dest='ping',type=bool,help='发布成功后是否Ping搜索引擎，默认开启',default=True)
parser.add_argument('--proxy',dest='proxy',type=str,help='匿名HTTP代理地址 格式IP:Prot，默认不启用',default=None)
parser.add_argument('--replace',dest='replace',type=str,help='关键词替换文件',default='replace.txt')
args = parser.parse_args()

def initStatus(website,daily):
    global status
    for web in re.split(r'\s+',website):
        status[web] = {'daily':daily,'published':0}

def Spider(URL,DATA = None, headers = dict(), proxy = None):
    headers.update({'User-Agent':UserAgent,'Referer':'https://www.google.com/'})

    if not DATA == None and type(DATA) is str:
        DATA = DATA.encode('utf-8')

    elif not DATA and type(DATA) is dict:
        DATA = urllib.parse.urlencode(DATA).encode('utf-8')

    if not proxy == None:
        proxy_handler = urllib.request.ProxyHandler({'http': proxy})
        opener = urllib.request.build_opener(proxy_handler)
        urllib.request.install_opener(opener)

    try:
        req = urllib.request.Request(URL,DATA,headers)
        content = urllib.request.urlopen(req).read().decode('utf-8')
        return content
    except urllib.error.URLError as e:
        return None


def PingGoogle(URL,proxy = None):
    result = Spider(GoogleIndexAPI,'{"url":"%s","type":"URL_UPDATE"}'%URL,{'content-type':'application/json'},proxy)
    return result


def DoReplace(content):
    if(not os.path.exists(args.replace)):
        return content
    filefd = open(args.replace,"rb")
    replaces = filefd.readlines()
    for match in replaces:
        match = match.decode('utf-8').strip('\n')
        if not len(match):continue
        find = replacere.search(match).group(1)
        replace = replacere.search(match).group(2)
        if find[:3] == 're:':
            content = re.sub('(?i)%s'%find[3:],replace,content)
        else:
            content = content.replace(find,replace)
    filefd.close()
    return content


try:
    keyfd = open(args.keyfile,'r')
except Exception as e:
    print(e)
    exit(-1)

while True:
    line = keyfd.readline()
    if not line:break

    keyword = line.strip()
    url = "%s?%s=%s&count=60&key=yht123hito"%(args.origin,args.query,urllib.parse.quote(keyword))
    try:
        content = Spider(url)
        content = json.loads(content)
        content = content['results']
        html = ''
        for meta in content:
            if len(meta['title']) < 10 or len(meta['description']) < 50: continue
            html = "%s<h2>%s</h2>\r\n<p>%s</p>\r\n"%(html,meta['title'].strip(),meta['description'].strip())
        article = {'post_title':keyword,'post_content':DoReplace(html)}
        print(article)
    except:
        pass
