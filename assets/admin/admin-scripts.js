jQuery(document).ready(function ($) {
  // Upload global images
  $("#upload_global_images_button").click(function (e) {
    e.preventDefault();

    var frame = wp.media({
      title: "Select or Upload Images",
      multiple: true,
      library: {
        type: "image",
      },
      button: {
        text: "Use these images",
      },
    });

    frame.on("select", function () {
      var attachmentIds = [];
      var attachments = frame.state().get("selection").toJSON();

      attachments.forEach(function (attachment) {
        attachmentIds.push(attachment.id);
        $("#wp_slideshow_global_images_container").append(
          '<div class="wp_slideshow_image" data-image-id="' +
            attachment.id +
            '">' +
            '<img src="' +
            attachment.url +
            '" width="100">' +
            '<button class="remove_image">Remove</button>' +
            "</div>"
        );
      });

      // Update hidden input field with comma-separated image IDs
      var currentImages = $("#wp_slideshow_global_images").val();
      if (currentImages) {
        currentImages += "," + attachmentIds.join(",");
      } else {
        currentImages = attachmentIds.join(",");
      }
      $("#wp_slideshow_global_images").val(currentImages);

      // Debugging: Log updated hidden input value
      console.log(
        "Updated Global Images: " + $("#wp_slideshow_global_images").val()
      );
    });

    frame.open();
  });

  $("#wp_slideshow_global_images_container").on(
    "click",
    ".remove_image",
    function () {
      var imageId = $(this).closest(".wp_slideshow_image").data("image-id");
      $(this).closest(".wp_slideshow_image").remove();

      var currentImages = $("#wp_slideshow_global_images").val().split(",");
      currentImages = currentImages.filter(function (id) {
        return id != imageId;
      });
      $("#wp_slideshow_global_images").val(currentImages.join(","));

      console.log(
        "Updated Global Images: " + $("#wp_slideshow_global_images").val()
      );
    }
  );
});
