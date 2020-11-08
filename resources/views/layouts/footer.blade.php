@php
$footer_items = \App\Page::where('bottom_menu_active', 1)->where('name', '!=', 'footer')->get();
$footer = \App\Page::where('bottom_menu_active', 1)->where('name', 'footer')->first();
@endphp

<footer class="lazy" data-bg="url('{{url('/default/footer.jpg')}}'" style="background-position: center; background-size: cover">
    <div class="footer-logo">
        <div class="footer-logo">
            <img src="{{url('/default/szop-sam.png')}}">
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-12 col-sm-12">
                <ul>
                   <div class="d-flex footer-menu">
                       {{menu('top_menu')}}

                   </div>
                    <li class='justify-content-center d-flex w-100'>
                        <a href="http://raccmoon-craft.pl/strona/privacy_policy" target="_self" style="">

                            <span>Privacy policy</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-sm-12">
              <div class="col-sm-12 d-flex justify-content-center">
                  <a href="https://www.instagram.com/raccmoon.craft/" class="icon-social"><img data-src="{{url('/default/ig-icon.png')}}" class="w-100 lazy"></a>
                  <a href="https://www.facebook.com/raccmoon.craft/" class="ml-3 icon-social"><img data-src="{{url('/default/fb-icon.png')}}" class="w-100 lazy"></a>

              </div>
            </div>
        </div>
    </div>

</footer>
<md-dialog :md-active.sync="$preview.dialog">
    <div class="w-100 d-flex justify-content-center" style="max-height: 100%" @click="$preview.close()">
        <img :src="getSrc($preview.url)" style="max-height: 100%">
        <md-button class="md-raised md-primary" style="position: absolute; top: 10px; right:10px">
            Zamknij
        </md-button>
    </div>
</md-dialog>