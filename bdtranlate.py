#!/usr/bin/env python
# -*- coding=utf-8 -*-
import sys,urllib2,urllib,json,time

class TrsltError(Exception):
    def __init__(self,*args):
        self.message=args[0]
    def __str__(self):
        return repr(self.message)

class Translator():
    def __init__(self,string,key="T93TH0Y1x5262LZSkHr5tLZx",api="http://openapi.baidu.com/public/2.0/bmt/translate",slang="auto",tlang="auto"):
        self.string=string
        self.key=key
        self.api=api
        self.slang=slang
        self.tlang=tlang
    def setlang(self,slang,tlang):
        self.slang=slang
        self.tlang=tlang

    def translate(self):
        apis={'client_id':self.key,'q':self.string,'from':self.slang,'to':self.tlang}
        baiduapi=self.api+"?"+urllib.urlencode(apis)
        try:
            f=urllib2.urlopen(baiduapi,timeout=20).read()
        except Exception, e:
            raise TrsltError(e.message)
        
        result=json.loads(f)
        if result.has_key('error_code'):
            error_msg='Translate Error: '+result['error_msg']
            raise TrsltError(error_msg)
        else:
            #print (result['trans_result'][0]['src'],result['trans_result'][0]['dst'])
            return (result['trans_result'][0]['src'].encode('utf-8'),result['trans_result'][0]['dst'].encode('utf-8'))



def getargu(arg):
    index=sys.argv.index(arg)
    return sys.argv[index+1]

if __name__ == '__main__':
    keyfile=getargu('-k')
    resultfile=getargu('-r')
    slang=getargu('-slang')
    tlang=getargu('-tlang')
    try:
        ttl=int(getargu('-t'))
    except:
        ttl=10

    try:
        kfile=open(keyfile,'r')
        rfile=open(resultfile,'a')
    except Exception,e:
        print e
        sys.exit()

    while True:
        try:
            key=kfile.readline().strip()
            if not key:break
            trs=Translator(key,slang=slang,tlang=tlang)
            resu=trs.translate()
            print "%s -->> %s"%resu
            rfile.write(resu[1]+'\n')
            rfile.flush()
            time.sleep(ttl)
        except TrsltError,e:
            print e
            continue
        except KeyboardInterrupt,e:
            break
        except Exception,e:
            print e
            break
    kfile.close()
    rfile.close()
