<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>پنل تولید لینک دانلود</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./font/font.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>تولید لینک دانلود ریدایرکت</h2>
        <form id="linkForm">
            <textarea name="original_links" id="original_links" placeholder="لینک‌های اصلی را وارد کنید، هر کدام در یک خط" required></textarea>
            <div class="box_center_flex_num">
                <div class="box_links_numbers">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                    </svg>
                    <div id="links_numbers">تعداد لینک وارد شده: 0</div>
                </div>
            </div>
            <div class="box_radio">
                <label>روش تولید کد کوتاه:</label>
                <p class="title_radio">خودکار</p>
                <div class="btnRadio3">
                    <input type="radio" name="short_code_method" value="auto" id="btnRadio3-1" checked />
                    <label for="btnRadio3-1"></label>
                </div>
                <p class="title_radio">دستی</p>
                <div class="btnRadio3">
                    <input type="radio" name="short_code_method" value="manual" id="btnRadio3-2" />
                    <label for="btnRadio3-2"></label>
                </div>
                <p class="title_radio">ثابت</p>
                <div class="btnRadio3">
                    <input type="radio" name="short_code_method" value="fixed" id="fixed_structure btnRadio3-3" />
                    <label for="fixed_structure btnRadio3-3"></label>
                </div>
            </div>
            <br>
            <div id="short_code_div" style="display: none;">
                <textarea id="manual_short_codese" class="manual_text" name="manual_short_codes" placeholder="کدهای کوتاه را به ازای هر لینک وارد کنید، هر کد در یک خط"></textarea>
            </div>
            <button type="submit">تولید لینک‌ها</button>
        </form>
        <div id="loading" style="display: none;">
            <div class="spinner"></div>
        </div>
        <div id="link-output" style="display: none;">
            <p>تعداد لینک‌های تولید شده: <span id="linkCount"></span></p>
            <ul id="redirectLinks"></ul>
        </div>
    </div>
    <form action="inc/show_links.php" method="get">
        <button class="admin_show" type="submit">نمایش لینک‌ها
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </button>
    </form>
    <script src="./js/script.js"></script>
</body>
</html>