$(document).ready(function () {
  function isValidURL(url) {
    return /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})(\/[^\s]*)?$/i.test(url);
  }
  function containsDangerousCode(input) {
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
    ].some((pattern) => pattern.test(input));
  }
  $('input[name="short_code_method"]').change(function () {
    $("#short_code_div").toggle("manual" === $(this).val());
  }),
    $('textarea[name="original_links"]').on("input", function () {
      var linkCount = $(this)
        .val()
        .trim()
        .split("\n")
        .filter((line) => "" !== line.trim() && isValidURL(line)).length;
      $("#links_numbers").text("Number of entered links: " + linkCount);
    }),
    $("#linkForm").submit(function (event) {
      event.preventDefault(), $("#loading").show(), $("#link-output").hide();
      var links = $('textarea[name="original_links"]')
        .val()
        .trim()
        .split("\n")
        .map((line) => line.trim());
      if (!links.every(isValidURL)) {
        alert("All links must be valid."), $("#loading").hide();
        return;
      }
      var method = $('input[name="short_code_method"]:checked').val(),
        shortCodes = $('textarea[name="manual_short_codes"]').val().trim().split("\n");
      if ("manual" === method && links.length !== shortCodes.length) {
        alert("The number of links and entered short codes must be equal."),
          $("#loading").hide();
        return;
      }
      if (shortCodes.some(containsDangerousCode)) {
        alert("⚠️ The short codes contain dangerous commands and are not allowed."),
          $("#loading").hide();
        return;
      }
      $.ajax({
        type: "POST",
        url: "",
        data: $(this).serialize(),
        dataType: "json",
        success: function (response) {
          response.error
            ? alert(response.error)
            : ($("#linkCount").text(response.link_count),
              $("#redirectLinks").empty(),
              response.redirect_links.forEach(function (link) {
                $("#redirectLinks").append(
                  '<li><a href="' + link + '" target="_blank">' + link + "</a></li>"
                );
              }),
              $("#loading").hide(),
              $("#link-output").show());
        },
        error: function () {
          alert("Error sending data"), $("#loading").hide();
        },
      });
    }),
    $("#manual_short_codes").on("input", function () {
      containsDangerousCode($(this).val().trim()) &&
        (alert("⚠️ The entered code contains dangerous commands and is not allowed."),
        $(this).val(""));
    }),
    $("#original_links").on("input", function (event) {
      let decodedLinks = event.target.value.split("\n").map((line) => {
        try {
          return decodeURIComponent(line);
        } catch (error) {
          return line;
        }
      });
      event.target.value = decodedLinks.join("\n");
    });
});
