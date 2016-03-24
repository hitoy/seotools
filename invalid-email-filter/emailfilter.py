#!/usr/bin/env python
# -*- coding:utf-8 -*-
# Author Hito https://www.hitoy.org/
import re,sys,socket,dns.resolver
emaildomain = re.compile(r"@([a-zA-Z0-9\.\-\_]+\.[a-zA-Z]{2,6})",re.I)
#print emaildomain.findall("vip@hitoy.org")
#exit()
def is_domain_exists(domain):
    try:
        ip = socket.getaddrinfo(domain,'http')
        if ip[0][4][0]:
            return True
    except:
        return False

def is_mx_exists(domain):
    try:
        answers = dns.resolver.query(domain,'MX')
        return True
    except dns.resolver.NoAnswer:
        return False
    except BaseException,e:
        return False

savekeyfile = None
savefile = None
if len(sys.argv) == 1:
    print "Please enter a email list!"
    sys.exit(-1)
elif len(sys.argv) == 2:
    emailkeyfile = sys.argv[1]
elif len(sys.argv) == 3:
    emailkeyfile = sys.argv[1]
    savekeyfile = sys.argv[2]
 
try:
    emailfile = open(emailkeyfile,"r")
except Exception,e:
     print e
     sys.exit(-1)

if savekeyfile:
   try:
       savefile = open(savekeyfile,"w")
   except Exception,e:
        print e
        sys.exit(-1)


while True:
    line = emailfile.readline()
    if not line:
        break
    line = line.strip()
    if not line:
        continue
    
    domainlist = emaildomain.findall(line)
    if len(domainlist) == 0:
        continue
    domain = domainlist[0]
    if not is_mx_exists(domain):
        continue
    if not savefile:
        print line
    else:
         savefile.write(line+"\r\n")
