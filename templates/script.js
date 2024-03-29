$(document).ready(function () {
    $('.search-box input[type="text"]').on('keyup input', function () {
        /* Get input value on change */
        var term = $(this).val();
        var resultDropdown = $(this).siblings('.result');
        if (term.length) {
          $.get('http://localhost/php/newweek/livesearch.php', { query: term }).done(function (data) {
              // Display the returned data in browser
              resultDropdown.html(data);
            });
        } else {
          resultDropdown.empty();
        }
      });

    // Set search input value on click of result item
    $(document).on('click', '.result p', function () {
        $(this).parents('.search-box').find('input[type="text"]').val($(this).text());
        $(this).parent('.result').empty();
      });
  });
