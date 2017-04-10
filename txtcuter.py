#!/usr/bin/env python
# -*- coding:utf-8 -*-
# Author: Hito Yang vip@hitoy.org
import sys,math
files = False
lines = 1000
subfix = "cutting_"
try:
    files = sys.argv[1]
except:
    print "Please Input Text Files You Want to Cut!"
    exit()

try:
    lines = int(sys.argv[2])
except:
    lines = 1000

try:
    subfix = sys.argv[3]
except:
    subfix = "cutting_"


try:
    fd = open(files,"rb")
except Exception,e:
    print e
    exit()


line = fd.readline()
targetfd = False
i=0
n=0
while line:
    no = math.ceil(n/lines)+1
    filename = "%s%d.txt"%(subfix,no)
    if targetfd == False:
        targetfd = open(filename,"wb+")
    if i==lines:
        targetfd.close()
        targetfd = False
        i=0
    elif i<lines:
        targetfd.write("%s\r\n"%line.strip())
        i+=1
    n+=1
    line = fd.readline()
