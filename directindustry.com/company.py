#!/usr/bin/env python
# -*- coding:utf-8 -*-
import collection,sys,sqlite3,re,json,time
companylistre = re.compile(r"window\.ve\.floatingBar\.tree\s=\s?([^\r\n;]*)",re.I|re.M)
productlistre = re.compile(r"<a\sname=\"product-item_(\d+)\"\sclass=\"product-item-anchor\"><\/a>",re.I|re.M)


headersre = re.compile(r"productPictureViewerDatas\[\d*\]\s\=\s(.*)",re.I|re.M)
contentre = re.compile(r"<div\sclass=\"clear\"><\/div>([\s\S]*)<div\sclass=\"stand-links\">",re.I|re.M)


def GetContents(html):
    try:
        j = headersre.findall(html)
        obj = json.loads(j[0].strip(";"))
        title = "%s - %s"%(obj['keyword'],obj['model'])
        imgs = list()
        imgs.append(obj['mainPictureUrl'])
        for i in obj['thumbsList']:
            imgs.append(obj['thumbsList'][i].replace("images_di/photo-pc/","images_di/photo-g/"))
    except:
        title = False
        imgs = False
    try:
        k = contentre.findall(html)
        content = k[0]
        content = re.sub(r"<([\w\d]*)\s([^>]*)>","<\g<1>>",content).replace("<div>","").replace("</div>","").replace("<span>","").replace("</span>","").replace("  "," ").replace("\n","")
    except:
        content = False
    return title,imgs,content
    




companys = """
    create table IF NOT EXISTS __companys (
       id  INTEGER PRIMARY KEY autoincrement, 
       companyid char(10) unique not null,
       companyname char(100) not null,
       productsid text not null
     );
"""
products = """
    create table IF NOT EXISTS __products (
       id INTEGER PRIMARY KEY autoincrement, 
       companyname char(10) not null,
       productid char(10) not null,
       title char(100) not null,
       images char(255) not null,
       content text not null
    );
"""


try:
    db = sqlite3.connect("endb")
    db.execute(companys)
    db.execute(products)
except Exception,e:
    print e
    exit()


def getcompany(html):
    obj = list()
    try:
        lists = companylistre.search(html).groups()
        tmp = json.loads(lists[0].strip())
        lens = len(tmp)
        tmp = tmp[lens]['items']
    except:
        print "Get Company Error"
        return False
    for company in tmp:
        obj.append({"companyid":company['id'],"companyname":company['label']})
    return obj

def getproductid(companyname,companyid):
    time.sleep(0.1)
    urls = 'http://www.directindustry.com/prod/%s-%d.html'%(companyname.lower().replace(" ","-"),companyid)
    try:
        tmp = collection.Collection(urls)
        html = tmp.get_content()
    except:
        return False
    try:
        return productlistre.findall(html)
    except Exception,e:
        print e
        return False

def getproduct(productid,companyid):
    time.sleep(0.1)
    urls = "http://www.directindustry.com/ajax/get-product-detail.html?productId=%s&companyId=%s&numCol=1"%(productid,companyid)
    tmp = collection.Collection(urls)
    tmp.add_header("X-Requested-With","XMLHttpRequest")
    html = tmp.get_content()
    return html

if len(sys.argv) == 1:
    print "Max Input A KeyWord at Least"   
    exit()
else:
    for offset in range(len(sys.argv)-1):
        url = "http://www.directindustry.com/tab/%s.html"%sys.argv[offset+1].replace(" ","-")
        try:
            tmp  = collection.Collection(url)
            html = tmp.get_content()
        except Exception,e:
            print "Get %s Error,%s"%(url,e)

        #GET COMPANY
        companys = getcompany(html)
        if companys == False:
            continue
        for company in companys:
            companyname = company['companyname']
            companyid = company['companyid']
            products = getproductid(companyname,companyid)
            if products:
                sql = "insert into __companys (companyid,companyname,productsid) values (\"%s\",\"%s\",\"%s\");"%(companyid,companyname,",".join(products))
            else:
                print "Get %s Products Faile!"%companyname
                continue
            try:
                db.execute(sql)
                print "Get Company: <<%s>> Storaged!"%companyname
                db.commit()
            except Exception,e:
                print "Get Company: <<%s>> Not Storaged! %s"%(companyname,e)

        print "============================================================="
        print "Starting to Collection Detail Product"
        offset = 0
        con = db.cursor()
        while True:
            sql = "select id,companyid,productsid,companyname from __companys limit %s,1"%offset
            d = con.execute(sql)
            data = d.fetchone()
            if data == None:
                break
            else:
                for productid in data[2].split(","):
                    html = getproduct(productid,data[1])
                    if html:
                        title,imgs,content = GetContents(html)
                    else:
                        print "Get Product Error, Going Next"
                        continue
                    if title == False or imgs == False or content == False:
                        print "Parser Content Error, Going Next"
                        continue
                    newsql = "insert into __products (companyname,productid,title,images,content) values(\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")"%(data[3].encode("utf-8"),productid,title.encode("utf-8"),",".join(imgs),content.encode("utf-8"))
                    try:
                        db.execute(newsql)
                        db.commit()
                        print "Get <%s> Storage!"%title.encode("utf-8")
                    except:
                        print "Get <%s> Not Storage!"%title.encode("utf-8")
                    
            offset +=1









