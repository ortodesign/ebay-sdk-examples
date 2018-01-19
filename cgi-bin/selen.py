# encoding=utf8
import os
import sys
import urllib
import time
import json
from selenium import webdriver
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from selenium.common.exceptions import NoSuchElementException
# from selenium.webdriver.common.keys import Keys

# kill older proccess
os.system("taskkill /im geckodriver.exe /f")
os.system("taskkill /im firefox.exe /f")

# Set the MOZ_HEADLESS environment variable which casues Firefox to start in headless mode.
os.environ['MOZ_HEADLESS'] = '1'

# Select your Firefox binary.
binary = FirefoxBinary('C:\\Program Files\\Mozilla Firefox\\firefox.exe', log_file=sys.stdout)

driver = webdriver.PhantomJS()
# driver = webdriver.Firefox(firefox_binary=binary)

q_txt = 'HTC One'

targetUrl = urllib.quote_plus('https://www.citilink.ru/search/?text='+q_txt)
print('getting: '+targetUrl)
driver.get('https://www.citilink.ru/search/?text='+q_txt)  # &p=7
time.sleep(3)
product = {}
p3 = []

try:
    totalpages = driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a)[last()]').get_property('textContent').strip()
    print('in try '+totalpages)
except NoSuchElementException:
    curData = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // div[ @ data-list-id ]')
    for dat in curData:
        print(dat.get_attribute('data-params'))
        p3 = json.loads(dat.get_attribute('data-params'))
        product[p3['id']] = {
            'id': p3['id'],
            'title': p3['shortName'],
            'price': p3['price']
        }
else:
    # html = driver.page_source
    print('Total pages: '+totalpages)

    if int(totalpages) > 1:
        for page in range(1, int(totalpages)+1):
            driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a)['+ str(page) +']').click() # клик по пагинации
            time.sleep(3)
            # curTitle = driver.find_elements_by_xpath('.//*[@id="subcategoryList"]//span/a')
            # curPrice = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // span[1] / span / ins[1]')
            curData = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // div[ @ data-list-id ]')
            for dat in curData:
                print(dat.get_attribute('data-params'))
                p3 = json.loads(dat.get_attribute('data-params'))
                product[p3['id']] = {
                    'id': p3['id'],
                    'title': p3['shortName'],
                    'price': p3['price']
            }
for k, v in product.iteritems():
    # print(k)
    # print(v)
    print('Id: ' + str(v['id']) + ' Title: ' + v['title'] + ' Price: ' + str(v['price']))

print(product)
driver.close()
