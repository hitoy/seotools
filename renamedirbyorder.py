#!/usr/bin/env python
# -*- coding:gbk -*-
import os,sys,re
mydir = sys.argv[1]
dirlist = os.listdir(mydir)
filenamere=re.compile(r"^(.+)\.([^\.]*)$",re.I|re.M)
i=1
for filename in dirlist:
    myre = filenamere.findall(filename)
    dirname = os.path.abspath(mydir)
    oldname = "%s\%s"%(dirname,filename)
    newname = "%d.%s"%(i,myre[0][1])
    i+=1
    cmd = ("rename \"%s\" %s"%(oldname,newname))
    print cmd
    os.system(cmd)
