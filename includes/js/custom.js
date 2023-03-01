var closeIcons = document.getElementsByClassName("tag-input");

function update_tags() {
  var ajaxData = {
    action: "update_status_db",
    id: jQuery(this).parent().data("id"),
    vendorId: jQuery(this).parent().data("vendor"),
    type: jQuery(this).parent().data("type"),
    tag: jQuery(this).val().trim(),
  };

  // console.log("ASDASDA", ajaxData)

  jQuery.ajax({
    type: "POST",
    url: data.ajaxurl,
    data: ajaxData,
    success: function (response) {
      console.log("Data returned: " + response);
    },
    error: function () {
      alert("FAILED TO POST DATA!!");
    },
  });
}

for (i = 0; i < closeIcons.length; i++) {
  closeIcons[i].addEventListener("input", update_tags);
}
