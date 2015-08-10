#!/usr/bin/env python
# -*- coding:utf-8 -*-
from collection import *
import re
import sys
import urllib

key=urllib.quote(sys.argv[1])
path=sys.argv[2] if len(sys.argv)==3 else "./"

imgreg = re.compile(r"imgres\?imgurl=([^&]+)&")
filenamereg=re.compile(r"[^/]*$")

def downimg(url,path="./"):
    filename=filenamereg.search(url).group(0)
    imgcontent=Collection(url).content
    if len(imgcontent)>0:
        imgpath=open(path+filename,"wb")
        imgpath.write(imgcontent)
        imgpath.close()


class GoogleImg(Collection):
    def get_img_list(self):
        try:
            return imgreg.findall(self.content)
        except:
            return []

a = GoogleImg('https://www.google.com/search?q=%s&tbm=isch'%key)

imglist = a.get_img_list()

for img in imglist:
    print "get img %s"%img
    downimg(img,path)
