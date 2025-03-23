/**
 * Settings page JavaScript for CG Media Library Item
 */
(function ($) {
  "use strict";

  $(function () {
    // Initialize color pickers with live preview
    $(".cg-color-picker").wpColorPicker({
      change: function (event, ui) {
        // Get the color value
        var color = ui.color.toString();

        // Get the target element from data attribute
        var target = $(this).data("target");

        // Update the preview
        updateColorPreview(target, color);
      },
      clear: function (event) {
        // Get the target element from data attribute
        var target = $(this).data("target");

        // Get the default color
        var defaultColor = $(this).data("default-color");

        // Update the preview with default color
        updateColorPreview(target, defaultColor);
      },
    });

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

    // Function to update color preview
    function updateColorPreview(target, color) {
      var selector =
        "#cg-media-item-preview" +
        (cgMediaLibrarySettings.colorMap[target] !== ".media-item"
          ? " " + cgMediaLibrarySettings.colorMap[target]
          : "");
      var property = cgMediaLibrarySettings.cssProperties[target];

      // Special case for hover state
      if (selector.includes(":hover")) {
        // Create a style element for hover state if it doesn't exist
        if ($("#cg-hover-style").length === 0) {
          $("head").append('<style id="cg-hover-style"></style>');
        }

        // Update the hover style
        $("#cg-hover-style").text(
          selector + " { " + property + ": " + color + " !important; }"
        );

        // Also update the download button to show the hover effect when hovered in the preview
        $("#cg-media-item-preview .media-item__download-btn")
          .on("mouseenter", function () {
            $(this).css(property, color);
          })
          .on("mouseleave", function () {
            $(this).css(property, "");
          });
      } else {
        // Update the regular style
        $(selector).css(property, color);
      }
    }

    // Typography live preview
    $(".cg-typography-field select, .cg-typography-field input").on(
      "change input",
      function () {
        var id = $(this).attr("id");
        var value = $(this).val();

        // Extract the field type from the ID
        var fieldType = id.replace("cg_typography_", "");

        // Update the preview
        updateTypographyPreview(fieldType, value);
      }
    );

    // Function to update typography preview
    function updateTypographyPreview(fieldType, value) {
      // Skip if this field type is not in our mapping
      if (!cgMediaLibrarySettings.typographyMap[fieldType]) {
        return;
      }

      var selector =
        "#cg-media-item-preview " +
        cgMediaLibrarySettings.typographyMap[fieldType];
      var property = cgMediaLibrarySettings.typographyProperties[fieldType];

      // Special handling for font-family
      if (property === "font-family" && value === "inherit") {
        value = "";
      }

      // Update the preview
      $(selector).css(property, value);
    }

    // Add hover effect to the download button in preview
    $("#cg-media-item-preview .media-item__download-btn").hover(
      function () {
        // Get the hover color from the color picker
        var hoverColor = $(
          "#cg_media_library_item_download_btn_hover_color"
        ).val();
        $(this).css("color", hoverColor);
      },
      function () {
        // Restore the normal color
        var normalColor = $("#cg_media_library_item_download_btn_color").val();
        $(this).css("color", normalColor);
      }
    );
  });
})(jQuery);
