<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ Session::token() }}">

    <title>PIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .wrapper {
            margin: 0 auto;
            border: 1px solid #eee;
            border-radius: 5px;
            max-width: 800px;
            padding: 20px;
            margin-top: 100px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
        }

        .input-wrapper .pin-input {
            width: 50px;
            margin: 3px;
            padding-left: 19px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form>
            <h5>PIN ...</h5>
            <br />

            <div class="input-wrapper" id="wrap">
                <input type="text" name=pin[] placeholder="0" class="pin-input form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
                <input type="text" name=pin[] placeholder="0" class="pin-input form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
                <input type="text" name=pin[] placeholder="0" class="pin-input form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
                <input type="text" name=pin[] placeholder="0" class="pin-input form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
            </div>
            <br />

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="secret">
                <label class="form-check-label" for="secret">
                    Secret mode
                </label>
            </div>
            <div class="input-group mb-3 mt-3">
                <span style="border-radius: 0;" class="input-group-text" id="inputGroup-sizing-default">Number of PIN (<= 9): </span>
                <input style="max-width: 80px" type="text" id="num" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#wrap :input:enabled:visible:first').focus();

            $('body').on('change', '#secret', function(e) {
                if ($(this).is(':checked')) {
                    $('#wrap .pin-input').attr('type', 'password')
                } else {
                    $('#wrap .pin-input').attr('type', 'text')
                }
            });

            $('#num').on('input', function() {
                const value = $(this).val();
                if (value) {
                    $('#wrap').empty();

                    const type = $('#secret').is(':checked') ? 'password' : 'text';
                    for (let i = 0; i < value; i++) {
                        $('#wrap').append(`<input type="` + type + `" name=pin[] placeholder="0" class="pin-input form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />`);
                    }
                }
            });

            $('body').on('keyup', '.pin-input', function(e) {
                if (this.value.length == this.maxLength) {
                    const next = $(this).next('.pin-input');
                    if (next.length) {
                        next.focus();
                    } else {
                        const pin = $("input[name='pin[]']").map(function() {
                            return $(this).val();
                        }).get();

                        $.ajax({
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: document.URL + 'pin',
                            type: 'POST',
                            data: {
                                pin
                            },
                            success: function(response) {
                                console.log('response:', response)
                                alert(response.message + ': ' + response.data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });

                    }
                }
            });

        });
    </script>
</body>

</html>
