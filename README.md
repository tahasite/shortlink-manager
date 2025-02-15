# FastLinkManager

FastLinkManager is a simple and efficient script for managing short links and redirects. This project is offered in two languages: English and Farsi.

With FastLinkManager, you can create short links both individually and in bulk at high speed on your root domain. It offers three methods for creating short links:

1. **Automatic**: The script automatically generates a unique short ID for each link.
2. **Custom**: You can manually define a custom short link for your URL.
3. **Fixed**: This method uses the original structure of the URL, but changes only the domain name.

You can easily manage, edit, and track your short links via the admin panel.

## Installation Guide

To install the script, follow these steps:

1. Upload the script folder (e.g., `redirect`) to the root directory of your web server.
2. Open the `install.php` file by navigating to:
   

3. Follow the installation steps and fill in your database information when prompted.
4. Once the installation is complete, you can start using the script.

## Live Demos

You can check out the live demos of the script:

- **[Demo (EN)](https://fastlinkmanager.tahasite.ir/EN/index.php)**: English version of the script.
  
- **[Demo (FA)](https://fastlinkmanager.tahasite.ir/FA/index.php)**: Persian version of the script.

## 🎥 FastLinkManager Installation & Usage Guide 🚀  

To easily set up and manage **FastLinkManager**, watch the following tutorials:  

### 🔹 Initial Installation & Setup 📂⚙️  
[![Watch on YouTube](https://img.shields.io/badge/Watch_on_YouTube-red?logo=youtube)](https://youtu.be/77C_K9R4BmI?si=QKjGWioS--HxJfC0)  

### 🔹 Managing & Using Short Links 🔗🎯  
[![Watch on YouTube](https://img.shields.io/badge/Watch_on_YouTube-red?logo=youtube)](https://youtu.be/kh6DFcGUZRY?si=j1jHafHq18hzH5DT)  

📌 With these tutorials, you can easily install and use **FastLinkManager**! 🚀✨


## Screenshots

Here are some screenshots showing the interface of the script:

### English Version:
![English Demo Screenshot](https://s6.uupload.ir/files/screencapture-localhost-redirect-index-php-2025-02-14-03_11_01_(1)_p5hk.png)

### Persian Version:
![Persian Demo Screenshot](https://s6.uupload.ir/files/screencapture-tahasite-ir-redirect-index-php-2025-02-14-02_53_32_fqxt.png)


## Telegram Channel Requirement

To support us and continue the development and distribution of this script and other future projects, we ask that you join our Telegram channel and provide your Telegram ID. This is a simple step to ensure that you're part of our community and receive updates about new versions.

Please join our Telegram channel here: [Tahasite Channel](https://t.me/tahasite_chanel)

After joining the channel, enter your numerical Telegram ID in the provided field to continue using the script.

## Features

- Create short links in three different modes: Automatic, Custom, and Fixed.
- High-speed creation of short links, both individually and in bulk.
- Easy management and editing of links via the admin panel.
- Multi-language support (Farsi and English).
- Manage redirects and track link usage.
- Simple user interface for better management.

## Important: .htaccess Configuration for Proper Redirects

By default, during the installation process, the `.htaccess` file will be automatically configured to ensure proper functionality of the redirects. However, if for any reason the configuration is not applied successfully, you need to manually ensure that the `.htaccess` file is correctly set up to handle redirects.

The correct configuration for `.htaccess` should look like this:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^/]+(?:/[^/]+)*)$ redirect/redirect.php?code=$1 [L]
RewriteRule ^([a-zA-Z0-9]+(?:/[a-zA-Z0-9]+)*)$ redirect/redirect.php?code=$1 [L]
```


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contributing

Feel free to fork the repository and create pull requests. Contributions are always welcome!

## Support

If you encounter any issues or have questions, feel free to contact us through our Telegram channel or open an issue in the repository.

# FastLinkManager

FastLinkManager یک اسکریپت ساده و کارآمد برای مدیریت لینک‌های کوتاه و ریدایرکت‌ها است. این پروژه به دو زبان ارائه می‌شود: فارسی و انگلیسی.

با استفاده از FastLinkManager، شما می‌توانید لینک‌های کوتاه را به صورت تکی و عمده با سرعت بسیار بالا روی دامنه روت خود ایجاد کنید. این اسکریپت سه روش مختلف برای ایجاد لینک کوتاه ارائه می‌دهد:

1. **خودکار**: اسکریپت به طور خودکار یک شناسه کوتاه منحصر به فرد برای هر لینک ایجاد می‌کند.
2. **دستی**: شما می‌توانید لینک کوتاه دلخواه خود را برای هر URL وارد کنید.
3. **ثابت**: در این روش، ساختار اصلی لینک حفظ می‌شود، فقط دامنه تغییر می‌کند.

همچنین شما می‌توانید لینک‌ها را در پنل مدیریت ویرایش و مدیریت کنید.

## راهنمای نصب

برای نصب اسکریپت، مراحل زیر را دنبال کنید:

1. پوشه اسکریپت (مثلاً `redirect`) را در دایرکتوری روت هاست خود آپلود کنید.
2. فایل `install.php` را باز کنید و به آدرس زیر بروید:
   

3. مراحل نصب را دنبال کنید و اطلاعات دیتابیس خود را وارد کنید.
4. بعد از اتمام نصب، می‌توانید از اسکریپت استفاده کنید.

## 🎥 ویدیوهای آموزشی نصب و استفاده از FastLinkManager 🚀  

برای راه‌اندازی و مدیریت بهتر **FastLinkManager**، ویدیوهای آموزشی زیر را مشاهده کنید:  

### 🔹 آموزش نصب و راه‌اندازی اولیه 📂⚙️  
[![مشاهده در یوتیوب](https://img.shields.io/badge/مشاهده_در_یوتیوب-red?logo=youtube)](https://youtu.be/77C_K9R4BmI?si=QKjGWioS--HxJfC0)  

### 🔹 آموزش مدیریت و استفاده از لینک‌های کوتاه 🔗🎯  
[![مشاهده در یوتیوب](https://img.shields.io/badge/مشاهده_در_یوتیوب-red?logo=youtube)](https://youtu.be/kh6DFcGUZRY?si=j1jHafHq18hzH5DT)  

📌 با این ویدیوها، **FastLinkManager** را به راحتی نصب و استفاده کنید! 🚀✨


## محدودیت کانال تلگرام

برای حمایت از ما و ادامه توسعه و انتشار این اسکریپت و پروژه‌های آینده، از شما خواهش می‌کنیم که به کانال تلگرام ما بپیوندید و آیدی تلگرام عددی خود را وارد کنید. این یک گام ساده برای اطمینان از این است که شما جزو جامعه ما هستید و از نسخه‌های جدید اسکریپت مطلع می‌شوید.

لطفاً به کانال تلگرام ما بپیوندید: [کانال طاهاسایت](https://t.me/tahasite_chanel)

پس از پیوستن به کانال، آیدی تلگرام عددی خود را در فیلد مربوطه وارد کنید تا بتوانید از اسکریپت استفاده کنید.

## ویژگی‌ها

- ایجاد لینک کوتاه در سه حالت مختلف: خودکار، دستی و ثابت.
- ایجاد لینک‌های کوتاه با سرعت بالا، به صورت تکی و عمده.
- امکان ویرایش و مدیریت لینک‌ها از طریق پنل مدیریت.
- پشتیبانی از چند زبان (فارسی و انگلیسی).
- مدیریت ریدایرکت‌ها و پیگیری استفاده از لینک‌ها.
- رابط کاربری ساده برای مدیریت بهتر.

## مجوز

این پروژه تحت مجوز MIT منتشر شده است - برای جزئیات به فایل [LICENSE](LICENSE) مراجعه کنید.

## مشارکت

برای مشارکت در پروژه، می‌توانید مخزن را فورک کرده و درخواست‌های pull ارسال کنید. تمامی مشارکت‌ها خوشامدگویی می‌شود!

## پشتیبانی

اگر با مشکلی مواجه شدید یا سوالی دارید، می‌توانید از طریق کانال تلگرام با ما تماس بگیرید یا یک مسئله در مخزن ثبت کنید.
