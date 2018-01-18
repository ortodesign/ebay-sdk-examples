import os
import sys
import urllib
import time
from selenium import webdriver
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
# from selenium.webdriver.common.keys import Keys

# Set the MOZ_HEADLESS environment variable which casues Firefox to start in headless mode.
os.environ['MOZ_HEADLESS'] = '1'

# Select your Firefox binary.
binary = FirefoxBinary('C:\\Program Files\\Mozilla Firefox\\firefox.exe', log_file=sys.stdout)

# driver = webdriver.PhantomJS()
driver = webdriver.Firefox(firefox_binary=binary)

q_txt = 'iphone 8'

targetUrl = urllib.quote_plus('https://www.citilink.ru/search/?text='+q_txt)
print('getting: '+targetUrl);
driver.get('https://www.citilink.ru/search/?text='+q_txt) #&p=7
time.sleep(5)
try:
    # totalpages = driver.find_element_by_xpath('.//li[@class="last"]/a').get_property('textContent').strip()
    totalpages = driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a)[last()]').get_property('textContent').strip()
    print('in try '+totalpages)
except:
    totalpages = driver.find_element_by_xpath('(.//li[@class="next"]/a)[last()]').get_property('textContent').strip()
    print('in except '+totalpages)

# html = driver.page_source
print('Total pages: '+totalpages)
driver.close()
# totalpages = list(int(totalpages))
for page in range(1, int(totalpages)+1):
    print(page)

