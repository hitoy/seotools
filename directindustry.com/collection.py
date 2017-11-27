#!/usr/bin/env python
# -*- coding:utf-8 -*-
import urllib2,urllib,sys,gzip,StringIO

class Collection:
    __headers = {"Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Encoding":"gzip, identity","Accept-Language":"en-US,en;q=0.8","Cache-Control":"max-age=0","Connection":"keep-alive","User-Agent":"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36","Referer":"http://www.directindustry.com/"}

    def __init__(self,url):
        self.content = ""
        self.url = url

    def add_header(self,key,value):
            self.__headers[key] = value

    def get_content(self):
        try:
            req = urllib2.Request(self.url,headers=self.__headers)
            page = urllib2.urlopen(req,timeout=10)
            rpheader = page.info()
            body = page.read()
        except Exception,e:
            self.content=''
            return None
        encoding = rpheader.get("Content-Encoding")
        if encoding == 'gzip':
            self.content=gz_decoding(body).strip()
        else:
            self.content=body.strip()
        return self.content


def gz_decoding(data):
    compressedstream = StringIO.StringIO(data)  
    gziper = gzip.GzipFile(fileobj=compressedstream)    
    data2 = gziper.read() 
    return data2   

if __name__ == "__main__":
    sys.exit(-1)
