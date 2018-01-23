#!/usr/bin/env python
# encoding=utf8
import os
import sys
import traceback
import logging

def citilink(query, minprice=100):
    import urllib
    import time
    import json
    from selenium import webdriver
    from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
    from selenium.common.exceptions import NoSuchElementException, StaleElementReferenceException, TimeoutException
    from selenium.webdriver.common.by import By
    from selenium.webdriver.support.ui import WebDriverWait
    from selenium.webdriver.support import expected_conditions as EC

    # from selenium.webdriver.common.keys import Keys

    def dollar():
        import urllib, json
        url = "https://www.cbr-xml-daily.ru/daily_json.js"
        response = urllib.urlopen(url)
        data = json.loads(response.read())
        return data

    dollar = dollar()['Valute']['USD']['Value']
    print(dollar)

    # kill older proccess
    os.system("taskkill /im geckodriver.exe /f")
    os.system("taskkill /im firefox.exe /f")
    os.system("taskkill /im phantomjs.exe /f")

    # Set the MOZ_HEADLESS environment variable which casues Firefox to start in headless mode.
    os.environ['MOZ_HEADLESS'] = '1'

    # Select your Firefox binary.
    binary = FirefoxBinary('C:\\Program Files\\Mozilla Firefox\\firefox.exe', log_file=sys.stdout)

    # set no images to firefox
    firefox_profile = webdriver.FirefoxProfile()
    firefox_profile.set_preference('permissions.default.stylesheet', 2)
    firefox_profile.set_preference('permissions.default.image', 2)
    # firefox_profile.set_preference('javascript.enabled', False)  # disable JS im not sure
    firefox_profile.set_preference('dom.ipc.plugins.enabled.libflashplayer.so', 'false')

    # driver = webdriver.PhantomJS()
    driver = webdriver.Firefox(firefox_binary=binary, firefox_profile=firefox_profile)
    driver.implicitly_wait(10)

    q_txt = query or 'Asus GeForce'

    targetUrl = urllib.quote_plus('https://www.citilink.ru/search/?text=' + q_txt)
    print('getting: ' + targetUrl)
    driver.get('https://www.citilink.ru/search/?text=' + q_txt)  # &p=7
    time.sleep(2)
    product = {}
    p3 = []

    try:
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.XPATH, '(.//div[@class="page_listing"]//li/a)[last()]')),
            message='totalpages widget is empty')
        totalpages = driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a)[last()]').get_property(
            'textContent').strip()
        print('in try ' + totalpages)
    except Exception as e:
        print e
        logging.error(traceback.format_exc())
        curData = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // div[ @ data-list-id ]')
        for dat in curData:
            print(dat.get_attribute('data-params'))
            p3 = json.loads(dat.get_attribute('data-params'))
            if p3['price'] / dollar > minprice:
                product[p3['id']] = {
                    'id': p3['id'],
                    'shortName': p3['shortName'],
                    'categoryName': p3['categoryName'],
                    'categoryId': p3['categoryId'],
                    'brandName': p3['brandName'],
                    'price': p3['price'] / dollar
                }
    else:
        # html = driver.page_source
        print('Total pages: ' + totalpages)

        if int(totalpages) > 1:
            for page in range(1, int(totalpages) + 1):  # if totalpages < 9 else int(totalpages)

                # data - page = "13"
                if page != 1:
                    WebDriverWait(driver, 10).until(
                        EC.element_to_be_clickable(
                            (By.XPATH, '(.//div[@class="page_listing"]//li/a[@data-page="' + str(page) + '"])')),
                        message='click widget is empty')

                    driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a[@data-page="' + str(page) + '"])').click()  # клик по пагинации
                # driver.find_element_by_xpath('(.//div[@class="page_listing"]//li/a)['+ str(page) +']').click() # клик по пагинации

                # curTitle = driver.find_elements_by_xpath('.//*[@id="subcategoryList"]//span/a')
                # curPrice = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // span[1] / span / ins[1]')

                # time.sleep(1)

                try:
                    WebDriverWait(driver, 10).until(
                        EC.presence_of_all_elements_located(
                            (By.XPATH, '.// *[ @ id = "subcategoryList"] // div[ @ data-list-id ]')),
                        message='curData widget is empty')
                    curData = driver.find_elements_by_xpath('.// *[ @ id = "subcategoryList"] // div[ @ data-list-id ]')
                except Exception as e:
                    print e
                    logging.error(traceback.format_exc())
                try:
                    for dat in curData:
                        print(dat.get_attribute('data-params'))
                        p3 = json.loads(dat.get_attribute('data-params'))
                        if p3['price'] / dollar > minprice:
                            product[p3['id']] = {
                                'id': p3['id'],
                                'shortName': p3['shortName'],
                                'categoryName': p3['categoryName'],
                                'categoryId': p3['categoryId'],
                                'brandName': p3['brandName'],
                                'price': p3['price']
                            }
                except Exception as e:
                    print e
                    logging.error(traceback.format_exc())

                print page
    if len(product) > 0:
        for k, v in product.iteritems():
            print('IdKey: ' + str(k) + ' IdVal: ' + str(v['id']) + ' shortName: ' + v['shortName'] + ' Price: ' + str(
                v['price']))
    else:
        print('Not found')
    driver.close()
    return product


# print(citilink('iphone 6'))

# kill older proccess
os.system("taskkill /im geckodriver.exe /f")
os.system("taskkill /im firefox.exe /f")
os.system("taskkill /im phantomjs.exe /f")
