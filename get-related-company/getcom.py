#!/usr/bin/env python
# -*- coding:utf-8 -*-
import urllib2,urllib,re,sys,StringIO

if len(sys.argv)<2:
	sys.stdout.write("Please Input Your KeyWord!\n")
	sys.exit()

url = "http://win.mofcom.gov.cn/cbgnew/search.asp"
referrer = "http://win.mofcom.gov.cn/"

key = sys.argv[1]

def getPOSTHTML(url,data):
	formdata = urllib.urlencode(data)
	req = urllib2.Request(url,headers={"User-Agent":"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36","Referer":referrer})
	f = urllib2.urlopen(req,formdata,timeout=15)
	content = f.read()
	return content

page = 1
companyre = re.compile(r"<b\sclass=\"px12_Arial\">(.*?)<\/b>",re.S)
textre = re.compile(r"<[^>]*>")
while True:
	content = getPOSTHTML(url,{"Ptype":"01","PageNo":page,"keyword":key,"p_area":"0","p_coun":"ALL","website":"all","email":"all","ekind":"all","eright":"all","HavePic":"ALL","pname":"LastUpdate","imageField32.x":"12","mageField32.y":"8"})
	list = companyre.findall(content)
	if not list:
		break
	comfile = open("company.txt","ab")
	for com in list:
		com = textre.sub("",com)
		com = com.replace("-->","").strip()
		print com
		comfile.write(com+"\r\n")
	comfile.close()
	page += 1

"""

<tr valign=center> 
                <td width="64%"> 
				11.<b class="px12_Arial">
ARPA			
					<!--<img src="http://win.mofcom.gov.cn/changewordimage/default.aspx?flag=" align="absmiddle"/>-->
					
				</b><!--******--><!--'=CompanyName-->
				 &nbsp;&nbsp;</td>
print companyre.search(test).group(1)
"""
