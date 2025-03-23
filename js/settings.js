/**
 * Settings page JavaScript
 */
(function ($) {
  "use strict";

  // Initialize color pickers with live preview
  $(function () {
    // Initialize all color pickers
    $(".cg-color-picker").wpColorPicker({
      change: function (event, ui) {
        // Get the color value
        var color = ui.color.toString();

        // Get the target element from data attribute
        var target = $(this).data("target");

        // Update the preview
        updatePreview(target, color);
      },
      clear: function (event) {
        // Get the target element from data attribute
        var target = $(this).data("target");

        // Get the default color
        var defaultColor = $(this).data("default-color");

        // Update the preview with default color
        updatePreview(target, defaultColor);
      },
    });

    // Function to update the preview
    function updatePreview(target, color) {
      var selector =
        "#cg-media-item-preview " + cgMediaLibrarySettings.colorMap[target];
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
