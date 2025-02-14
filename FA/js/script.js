$(document).ready(function () {
  function t(t) {
    return /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})(\/[^\s]*)?$/i.test(t);
  }
  function e(t) {
    return [
      /<\?php/gi,
      /system\s*\(/gi,
      /exec\s*\(/gi,
      /shell_exec\s*\(/gi,
      /passthru\s*\(/gi,
      /base64_decode\s*\(/gi,
      /eval\s*\(/gi,
      /`.*?`/gi,
      /rm\s+-rf\s+/gi,
      /drop\s+table/gi,
      /select\s+\*/gi,
    ].some((e) => e.test(t));
  }
  $('input[name="short_code_method"]').change(function () {
    $("#short_code_div").toggle("manual" === $(this).val());
  }),
    $('textarea[name="original_links"]').on("input", function () {
      var e = $(this)
        .val()
        .trim()
        .split("\n")
        .filter((e) => "" !== e.trim() && t(e)).length;
      $("#links_numbers").text("تعداد لینک وارد شده: " + e);
    }),
    $("#linkForm").submit(function (i) {
      i.preventDefault(), $("#loading").show(), $("#link-output").hide();
      var n = $('textarea[name="original_links"]')
        .val()
        .trim()
        .split("\n")
        .map((t) => t.trim());
      if (!n.every(t)) {
        alert("تمام لینک‌ها باید معتبر باشند."), $("#loading").hide();
        return;
      }
      var a = $('input[name="short_code_method"]:checked').val(),
        r = $('textarea[name="manual_short_codes"]').val().trim().split("\n");
      if ("manual" === a && n.length !== r.length) {
        alert("تعداد لینک‌ها و کدهای کوتاه وارد شده باید برابر باشند."),
          $("#loading").hide();
        return;
      }
      if (r.some(e)) {
        alert("⚠️ کدهای کوتاه شامل دستورات خطرناک هستند و مجاز نیستند."),
          $("#loading").hide();
        return;
      }
      $.ajax({
        type: "POST",
        url: "",
        data: $(this).serialize(),
        dataType: "json",
        success: function (t) {
          t.error
            ? alert(t.error)
            : ($("#linkCount").text(t.link_count),
              $("#redirectLinks").empty(),
              t.redirect_links.forEach(function (t) {
                $("#redirectLinks").append(
                  '<li><a href="' + t + '" target="_blank">' + t + "</a></li>"
                );
              }),
              $("#loading").hide(),
              $("#link-output").show());
        },
        error: function () {
          alert("خطا در ارسال داده‌ها"), $("#loading").hide();
        },
      });
    }),
    $("#manual_short_codes").on("input", function () {
      e($(this).val().trim()) &&
        (alert("⚠️ کد وارد شده شامل دستورات خطرناک است و مجاز نیست."),
        $(this).val(""));
    }),
    $("#original_links").on("input", function (t) {
      let e = t.target.value.split("\n").map((t) => {
        try {
          return decodeURIComponent(t);
        } catch (e) {
          return t;
        }
      });
      t.target.value = e.join("\n");
    });
});
