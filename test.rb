require 'rubygems'
require 'selenium-webdriver'

# Input capabilities
caps = Selenium::WebDriver::Remote::Capabilities.new
caps['browserstack.local'] = 'true'
caps['browserstack.localIdentifier'] = ENV['BROWSERSTACK_LOCAL_IDENTIFIER']
caps.setCapability("browserName", "android");
caps.setCapability("device", "Samsung Galaxy S7");
caps.setCapability("realMobile", "true");
caps.setCapability("os_version", "6.0");

driver = Selenium::WebDriver.for(:remote,
  :url => "http://USERNAME:ACCESS_KEY@hub-cloud.browserstack.com/wd/hub",
:desired_capabilities => caps)
driver.navigate.to "http://www.google.com"
element = driver.find_element(:name, 'q')
element.send_keys "BrowserStack"
element.submit
puts driver.title
driver.quit
