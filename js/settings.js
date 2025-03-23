/**
 * Settings page JavaScript for CG Media Library Item
 */
(function ($) {
  "use strict";

  // Initialize color pickers
  $(function () {
    // Initialize color pickers
    $(".cg-color-picker").wpColorPicker();

    // Tab functionality
    const $tabs = $(".nav-tab");
    const $tabContent = $(".tab-content");

    // Handle tab clicks
    $tabs.on("click", function (e) {
      e.preventDefault();

      // Set active tab
      $tabs.removeClass("nav-tab-active");
      $(this).addClass("nav-tab-active");

      // Show corresponding content
      const target = $(this).attr("href");
      $tabContent.hide();
      $(target).show();
    });

    // Font size and line height input validation
    $(".cg-font-size, .cg-line-height").on("input", function () {
      let value = $(this).val();

      // Clean the input value to ensure it has proper format
      if ($(this).hasClass("cg-font-size")) {
        // If there is no unit, add 'px' as default
        if (value && /^[0-9.]+$/.test(value)) {
          $(this).val(value + "px");
        }
      }

      // Prevent invalid characters for line height
      if ($(this).hasClass("cg-line-height")) {
        if (!/^[0-9.]*$/.test(value)) {
          $(this).val(value.replace(/[^0-9.]/g, ""));
        }
      }
    });

    // Live preview functionality for typography settings
    function updateTypographyPreview() {
      // Get current typography values
      const titleFontFamily = $("#cg_typography_title_font_family").val();
      const titleFontSize = $("#cg_typography_title_font_size").val();
      const titleFontWeight = $("#cg_typography_title_font_weight").val();
      const titleLineHeight = $("#cg_typography_title_line_height").val();

      const typeBadgeFontFamily = $(
        "#cg_typography_type_badge_font_family"
      ).val();
      const typeBadgeFontSize = $("#cg_typography_type_badge_font_size").val();
      const typeBadgeFontWeight = $(
        "#cg_typography_type_badge_font_weight"
      ).val();

      // Update preview styles
      $(".preview-title").css({
        "font-family": titleFontFamily === "inherit" ? "" : titleFontFamily,
        "font-size": titleFontSize,
        "font-weight": titleFontWeight,
        "line-height": titleLineHeight,
      });

      $(".preview-badge").css({
        "font-family":
          typeBadgeFontFamily === "inherit" ? "" : typeBadgeFontFamily,
        "font-size": typeBadgeFontSize,
        "font-weight": typeBadgeFontWeight,
      });
    }

    // Initial call to set up preview
    if ($(".typography-preview").length) {
      updateTypographyPreview();

      // Update on change
      $(".cg-typography-field select, .cg-typography-field input").on(
        "change input",
        function () {
          updateTypographyPreview();
        }
      );
    }
  });
})(jQuery);
