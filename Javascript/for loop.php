<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>for loop exercise</title>
</head>
<body>
    <script>
        haystack = new Array()
        haystack[4] = "Needle"
        haystack[11] = "Needle"
        haystack[17] = "Needle"

        for (let j = 0; j < 20; ++j) {
            if (haystack[j] == "Needle"){
                document.write("<br>- Found at location " + j + "<br>")
                continue
            }
            
            document.write(j + ", ")
        }
    </script>
</body>
</html>