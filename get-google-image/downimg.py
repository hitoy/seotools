#!/usr/bin/env python
#! -*- coding:utf-8 -*-
import re,collection
filenamereg=re.compile(r"[^/]*$")

def downimg(url,path="./"):
    filename=filenamereg.search(url).group(0)
    try:
        imgcontent=collection.Collection(url).content
        imgpath=open(path+filename,"wb")
        imgpath.write(imgcontent)
        imgpath.close()
    except BaseException,e:
        print e

