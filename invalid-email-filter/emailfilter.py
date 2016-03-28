#!/usr/bin/env python
# -*- coding:utf-8 -*-
# Author Hito https://www.hitoy.org/
import re,sys,socket,urllib2,dns.resolver
emaildomain = re.compile(r"@([a-zA-Z0-9\.\-\_]+\.[a-zA-Z]{2,6})",re.I)
domainre = re.compile(r"^[a-zA-Z0-9\.\-\_]+\.[a-zA-Z]{2,6}$",re.I)

def get_current_addr():
    try:
        return urllib2.urlopen("http://lab.hitoy.org/api/gettheip").read()
    except Exception,e:
        logging(str(e))
        return False

def get_domain_addr(domain):
    try:
        return socket.getaddrinfo(domain,"80")[0][4][0]
    except Exception,e:
        logging(str(e))
        return False

def is_domain_exists(domain):
    try:
        ip = socket.getaddrinfo(domain,'http')
        if ip[0][4][0]:
            return True
    except:
        return False

def get_mx_record(domain):
    try:
        record = list()
        mx = dns.resolver.query(domain,'MX')
        for i in mx:
            record.append((i.preference,i.exchange.to_text()))
        return record
    except dns.resolver.NoAnswer:
        return False
    except BaseException,e:
        return False

def logging(logstr,logfile="./filter.log"):
    log = open(logfile,"ab")
    log.write(logstr+"\n")
    log.close()


smtpserver = None 
current_addr = get_current_addr()
while not smtpserver:
    smtpserver = raw_input("\r\nPlease Input Current SMTP Address\r\nA domain name pointer to current Network address(A Record):").strip()
    if not domainre.findall(smtpserver):
        print "\r\nDomain invalid!"
        smtpserver = None
    elif not get_domain_addr(smtpserver):
        print "\r\nDomain not Exists!"
        smtpserver = None
    elif not current_addr == get_domain_addr(smtpserver):
        print "\r\nThe Domain your input does not point to the current IP\r\nPlease Add A Record to the domain you input!"
        smtpserver = None

mailfrom = "hi@%s"%smtpserver


def is_user_exists(mx_record,email):
    sock = socket.socket(socket.AF_INET,socket.SOCK_STREAM)
    sock.setsockopt(socket.SOL_SOCKET,socket.SO_REUSEADDR,1)
    sock.settimeout(8)
    sock.connect((mx_record,25))
    is_ok = sock.recv(1024).strip()
    if not is_ok[:3] == "220":
        logging(email+": "+is_ok)
        sock.close()
        return False
    sock.send("HELO %s\r\n"%smtpserver)
    resp = sock.recv(1024).strip()
    if not resp[:3] == "250":
        logging(email+": "+resp)
        sock.close()
        return False
    sock.send("Mail From:<%s>\r\n"%mailfrom)
    resp = sock.recv(1024).strip()
    if not resp[:3] == "250":
        logging(email+": "+resp)
        sock.close()
        return False
    sock.send("Rcpt To:<%s>\r\n"%email)
    resp = sock.recv(1024).strip()
    if not resp[:3] == "250":
        logging(email+": "+resp)
        sock.close()
        return False
    sock.close()
    logging(email+": email exists!")
    return True


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
    email = line.strip()
    if not email:
        continue
    #Get List domain
    domainlist = emaildomain.findall(email)
    if len(domainlist) == 0:
        continue
    domain = domainlist[0]
    mxlist = get_mx_record(domain)
    if not mxlist or len(mxlist) == 0:
        continue

    #if domain has a mx record
    for ex in mxlist:
        preference = ex[0]
        exchange = ex[1].strip(".")
        try:
            user_exists = is_user_exists(exchange,email)
        except socket.gaierror:
            continue
        except socket.timeout:
            continue
        except socket.error:
            continue
        except KeyboardInterrupt:
            break

        if user_exists:
            if savefile:
                savefile.write(email+"\r\n")
            else:
                print email
            break
        else:
            if savefile:
                print "Email %s does not exist"%email

emailfile.close()
if savefile:
    savefile.close()
