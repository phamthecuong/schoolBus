
<!DOCTYPE html>
<html lang="en-us" class="smart-style-5">
    <head>
    @include("layouts.partials.htmlheader")
    </head>
    <body class="@yield("body_class")">
        @include("layouts.partials.mainheader")
        @include("layouts.partials.sidebar")
        <div id="main" role="main">

            <!-- RIBBON -->
            
            <div id="content">
                @yield("content")
            </div>
        </div>
        @include("layouts.partials.footer")
        @include("layouts.partials.script")
    </body>

</html>