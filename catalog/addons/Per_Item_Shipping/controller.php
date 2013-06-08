<?php
/*
  $Id: controller.php v1.0 2013-04-20 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
// include the addon class (this is NOT the addons class; notice there is no `s` in the class name)
require_once(DIR_FS_CATALOG . 'includes/classes/addon.php');

// your addon must extend lC_Addon
class Per_Item_Shipping extends lC_Addon {
  /*
  * Class constructor
  */
  public function Per_Item_Shipping() {    
    global $lC_Language;    
   /**
    * The addon type (category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, other 
    */    
    $this->_type = 'shipping';
   /**
    * The addon class name
    */    
    $this->_code = 'Per_Item_Shipping';    
   /**
    * Inject the language definitions if they exist
    */ 
    if (file_exists(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml')) {        
      $lC_Language->injectAddonDefinitions(DIR_FS_CATALOG . 'addons/' . $this->_code . '/languages/' . $lC_Language->getCode() . '.xml');
    }    
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_shipping_item_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_shipping_item_description');
   /**
    * The developers name
    */    
    $this->_author = 'Loaded Commerce, LLC';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.loadedcommerce.com';    
   /**
    * The addon version
    */     
    $this->_version = '1.0.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.0.0.4.1'; // the addon is compatible with this core version and later   
   /**
    * The base64 encoded addon image used in the addons store listing
    */     
    $this->_thumbnail = '<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAYAAADxJz2MAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjAxMkVEMzhEMDgzMTFFMjg3QkJERUI0ODc0MjgyQUUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjAxMkVEMzlEMDgzMTFFMjg3QkJERUI0ODc0MjgyQUUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGMDEyRUQzNkQwODMxMUUyODdCQkRFQjQ4NzQyODJBRSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGMDEyRUQzN0QwODMxMUUyODdCQkRFQjQ4NzQyODJBRSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PmTqhxMAABqUSURBVHja7Fz5j17XWX7PXb919sX2zHiPlzhxkqbZ3C1pBQKBWIRA/QEh0d/4nT8BIUCCqr+UClQKCFREJSKoUCUq0QWStmmTOJsdO3E8jsfjWb59ufu9h+d9z51JCw6ZOAlNhcf6/I3n+757z3nO877v87znjJXWmu583f6XdQeCOwDeAfAOgP+Pv5xb/XD7le/cxlLYlK69SLrQFA97ZFfq5E3voywMKO1vUTZuUfPEOaofPkPjaxcoXHuV/Ol9drh5ZV9lduWg5dfiYOON9frBu9uu66WDaxepcfyjFLzxHK4xoMaRs6S8Cg0vPUNufZK82SUK1q+QU2/S1Klz1Hvp21REQ9zjYWpWXOq+8RK9uB5R1Sro6EfOURqF7xmsw+d+ZW8AfuBfXPl1QVTkczrLjufR+DjUQKgUVZRlO8p21kgRKQsBon4GGfi+fwEZBg3AkAUWedOLDxVx8Hv5sPMgALsr2ni9qouclOMNR6//aN12vYtFlt1M2muv43MvgN1blut3wcC1DxuizgcCVvkMcA7hr8coT89abnWcdjeK0eXoUbs2ec7ff3zO3seguZTFAUK8T3rca8admyfzoH+SrzJ+7ft8oZgcvze+8vxQ286LlCcXwcweHi/ixQu4zwALM9gFVlnCXMXjUOpnDEClLJ1n9wO4J9JB9/Gkt/2AjoNFy0FYIkeGaxcpzFNSflVyJIAk2y+f8XD2H6fKwXuJI7dIE8rDPuVBz8+iYDHpbi/qsH/cchxKuhsUbV0b441dJMhR/5XvXrKK5HnX9/qepa8jNTyda90B5zkdRAA0l3TwYQaQ+abj6E/T1pu/qy1vElmbbAxa2zxwTAUhavG/lUeUZ5QPWpR2N0kL7jZZrosi4eO5Rm4DgDZm5OFMLZLrVqhy4BSukVKW5ZSMh6TDXt1TaZ2SkKywd4qs/FdHUUqbz/x73ur0XxtHSSuNk1pl+fCLWRL/fhZH7dtL15q8ShVDTvcOYK7d2wDQqaTj4JcpjSftqk9uxWdCUgGlpIqCCkSTrRyQA9/j34TQVVxMOMoKLTlSJzGhoFDe33yruANU28MEnBo5ALYyNU91PFf3nybchIbbN2n1wnl6+bnn6OaNNfJUbk961qmpmkd22KJRNjzePXTmC2kwaL/bTJRjsTy/QoPOJhbRpSMf3ysD1W3QXdkcJoMCYBRpRgUXUJ2TBussvh5+jlCWR8EvAli80+CnAB7+WIrf5hpA+ScMfFaQzoaYTY+sqEVZd5WGUUyb/YQ2RxlAD2ht7QYmW9Ch6QrNVh1yPReX0DSmlKxKY4T0EaG4vxveYT0VNWemwPiUzn/vW3Tm/of3zkCFie2Fcz/5r1zuzMlbAZA8N3JF6UIA4mcOA70zEQZW+GmRA1BzXYiq12CpSByAnxcO1oWBLci1FfWHCa2uX6d2q00pwrhiZVSfmKITywtgikcuLhAMBtQbBVJEOI96oLuylIyLF8k88+WLty/oWPA8HlED429lNsVxgkXx9w4gctgeFgnMsFyZqOQ4y8WQkOn4b1AfMzdxwIzExGxJDRh8lkkuZISZfznAJbDHsjA9Vjv4twUqWpwXAZqNa3uQPv1uly6/egmgZrQwUSFnsor1waLwGFBYYo0ihRyIXCcpw0KKKGRkeIvjZVztGTQNcCoAe2ZhEekkuzUxMO7O1ZewSAPk5XmybZvermt1SwD9evUdyIfw27pIiTNNqjrDtONwA9Vyk98wUBsOQAvHSFiWlWzkOJWxSP6z5LkQUW2A4+/5ermRjgAR0AOjJMnIVRlVqy4uoShO8TksgvJtcgEgiIpc6VFue7hPbNJBYUKRhMMO+Y0qdeFwCjB35dhJ1J/x28gwTQ4Wj92VLMG79cLqnR4cpllEXvsCWeMNhGbMCb6A+M0K0J2ZEUUZxchVORiXp6k8dDkhzexjNgBwBl3t3BSvMf7yKF9nkLIk4Uwp651nWpJ7wZ/TqeRb20WR0fwZSCTOrGB0joukfE+d5Va1kVQcTQcP7afx2gW8nspCF0X+No+C9tomtd6TcEF1zKDVRpd/QEUSUZBZaYzcVMN6z9QcaviOlIoUk0wAAj/zhDNMlMOHJ8whr7nYANw0KwygOisrsyU/l3SBGeVSWAAoowVAs7J6C0vxlcQxwjjGdQAkQt0tEgrifIiqvjYZrZl4sJz3VWA771X92dUJ5IrXnmg9/U+/1O+s37feHtLW6xs01WjSwuwUTTc8angO1XzIGglR6DgAwBhw5eRQ07kjYcnsUdCNRZnfNZX1hHOaoaWAZrna5FAwUdtaioXNF0IoF1i8BAtzbXNEa1sBnWhkB/z15z9nT9Setrz6awAvlcVX5nGrENbvHUC1B9lsT2VB97fTreu/6Y5aD8T9dtPTXFF96kJyvbLaBkgoAGDhZK1CcxN1Wphu0IGZBr5vUBWgFlIVbamOO6hxXi/KhoNSheTJDAAXAqQWeVKAzUxWhz8J8KJBRz640e7T1ZsdWu9FFOMNy/OTdGzGnvfC9peGlLZufPNrV9Jg3AFG5xHGf4TH+FZdpXfD0FtX4SJ9xw/mo/af6/7aZz1U7Nn5JmVTNYrHAU0jdM/sb1K3H9B2v09bkB5bgxiT69Lr61tSiT3Ilka1QvOopPtnJmj/dBOMrVId1dHzeHEcKQAJwjsDMGlqUQK2pVw0wGCfiwaqdoA82xmFtHZlm250hpAxCteq0bmTc3RopkZ1CPr2OKbvXFy3PXdz0dfp4kQFC9Zbf3zj6itPFnHw3C7rylzMjsltTkvKULfNQCRlcQYoFPQ2NC9yfZjFieXVJAf5VYv8iSboMwdZktNcHNJh7gWGyEn4PgVrOoMIoI4x2RHd7Me0utGll1cBKuK5WoW0qFdoBaxZngeoUw1qAuQKRHENoBaeRTWE6zDKqdWPaLU1otV2SBGYtlB36d6VGbpneZIWJisUoEKv4/rXh7E4iAmkkVm8p+ZPUBIMwd6il9m1Ue5oyRMyw2qNKjaK3/ULYimNdy5uD0Br3JICYc8elor6P0La8Uh7/g8Qoo9SnnBqEn0lXRCbQfXgADCg6QWq8uuohhlkyCxcw7EowPcxJERIoyCj1jCize6QNvG83gvoR5fW6KmLkEG45TRYOdOo0PGlOZpt+PTU5W165UaPxgBoaapCHz8xS3cvTdAkmDbCz7YCTTc3UiHA3GSTjtQhyVxL2ByieAVxSgmKjF/LC9voahlzjsW1KsAQ4w1XX94V27cdwnn3mgjepHuTaLQJWielvXurVVUM1gMGkqhU9jv6AxUwE2HtieaDCiWFvMhSw56cQOLPyMOi1NKYJlA1D3DFTBjUlMZhQr1hQK1BAEBT6iD8WkgFzwLUZs0l0IYeO71Mnzi9QIeQNixEwGp7TBe2A+qHKU1AIx5dnqXFxX0YT0Zhd4MG40jypWVbZSOXLWbBst0S4Y739W9eodr+I0Qzc++6Qt/ayvkNsKZD6cZl4w4GMPcIZ6mTzDQehVuxXdsyK1UUJZBGKYp2Bei6YL2oxA+LQeTBIb+xH+Z7WNWmVF+WNC4AnUH1nEGeOwpAWcawhmRJAikC4ZzRIvJs1XdprRPQq1tDGo7HFKU5zU9U6cyBCXJg/NXEPGUoTBFEcogFEUdjmQq+oy9RxkOrUu9n0Rgk2aJ01EXknHhH0bz3HFi6BOViQMhxPbDAnVimCkBVeSQOlrtXMIwCqN5ZWV5kDFhJAi6kG6ONZBNPK9mAxW+RiqBWZdteM6iwZIVTFdKq+oRUXNaDPsCtcfGAWL++2aNXX75JozCi+aZPByY9qrqOVHvWgCl8tM02kut7lsg82GOLu2FxjLGlWJRMuXk86ARZ0DdLXurID0YH6tIRIAQTNQO33oEEAVJZbGVghegpFqfsAMSga9FkRdmBEa3Fqt90GsjSu0JoF1wtjQNci8ziKGE2670KdF5N1opdzfX2JtUx17sOTgnwA3jfEfKaAAWQXdg6TiHKdmEFbXExfMO8YIfDD7gW26eovX6kfemZzzfml77lOu4ly3a3La+64VSbkSq3H953Ic0qPkG4JP4iRfCa9Xxzy8WACm4aFHFpx8wmUMEqWeglukA8sWbRzJXBNhJFErVlS9BYLJskcZfGqBTNeR7InXNu0OQsUeoU26EpBnlhmhLsm9PcRIXrEdwkOdq0wvLC+OG8KLs70ovkTYKxX3Q3PpeOB5+zHS/BW24Ea5dWu2HrRwD9JQB5OY3Gl5XKujY3If4XQPfuRIRpClpLE4rlx9J294kFVLm87NtxuHJHhexCVpy/N70+A5oAVPpgSZJcBvPMOA7G23FMu4mvqE2u3I0AaUewJYRVQ8VkHck9RgOSFmZJ7gWQhJBOIa5TyBV+zrRtcjbyK1tA/hmnnWnoxBTjjEc9r1KrH8k3Lx/pbxVPqNoUZa8/26u59lpWa2630+D7tlvZwhWexU3+490ByKDJ5C1O9Afw/PODG69/Nh72Hq3ZyWTh1clzleQwXiUO4Twvm6GWkjDlvRBibysFhBkmjS3a7SCo0rJJ6Nmm0arF58miUBlRIqdKzcZinB0Jv48XJC/M946EbCbWLo4i5Dt+LUcU4zVlGhPjAD/HtKcwnmatSi6kEneBuPvCWxB8j2Tcn3pzEE6ttXoIruiJJV9T3dHXceuDewMQoLH0IBU183D4qE6j3xhefekX0vHoINOnitfHiLpXX7qCt/q0sjAFe1bBgHyqWqZNyj4/ZUC5YIhls4yU4IYpP5WsNUDvdGO0KUJlZ0arrKSnV5KRGwnsnyGU2E+XzQQJTXwbgZ0auXLc70gzwcY4fURECom0Bnt3uTWmHqTRx84cogUW/dKIgDNybanW7WFI61s96gxHxPp2umrTzPw05Sg2WKDpvYdwPLq7GLc/k7du/Fo67Hy6gtE5HJJTk6bNjhv3+0NxF+vr2/Ta6g3KMdi5ZoWWZydocX6G5qea1ERS9+EEuC4myIFhysXEGH8pPjbvl5QslAasqbyFhK8Su2a6x9ymsoSF3M1RCEtp3OC9wnhpQeY0arUlVFGXqVbBfZH3bnRCunBzSK1RSvsnfTp3bJYOTsMIxEO4nwZBf9Pa9oiubrRoNBrSFKzeIuYx05iQohdChiUYt3b8wZ4BTK8/9wc6SX7dQghUmg3DiNxUOs5vOUQrVSx68NgBig/OQfxGtNHpw1EE9PTNNsV6FTbMp4WZKToIW7Y0NwFAG3AMFQGLd9Zi9rlpVm6nlOCV+yImB3JutancKJFQTZHjWIawRMq0KVZ56ZdFI+E1LhrbYUHPXOvTpa2AKo5FZ5ca9JnTs7QPlo4LS4IoCaNQwL0K8PqDPsEk0/0r0wI820O2g5xbLcuWIbhepdi7E0lzRDCcQ901q89ajTkAS+YwA5HwPd+Czw3JSW2q46bLSMoJJtAfw/MiFLqjAH63S/+5vkGMU7VepSVU0ZXFGQDbpAUGtOZJfo0yA44UYE5DVslKyZ34Afww16cU2i7CxXgR01wLWFxdbeY1EG3DvdwYJHAxrE9zYdxdszU6imrHgv3NfiZMj6BfO8GYHEfR0oQPgPfBJ3vSZutHuTCd38+5UZeqAuJ/cu/dGH+iW+iILHjWQoLHNOY5N1ow4DaYpAvev22Kz+WtyDgMyMPk5uCDZyFyCz0J6mewUgB0BJ/bD2lru0NXbmzLitZqNZqfbtChBW4eTOEzVTH+DmQDU55ZlTHbOU+pROQSyyje4FGuEb7sVIZwGxvlDl0dKWNlrk4PHZ+ieSy+DfDHAIQtYS/CdepTiA6eTUaH9zVovuFK4eD00osy6UTbrBZ27JyoMUt6mK7ffG7vzQRkeJWrsl8nnZdyj8N4XpEFvF8ByWDzbhjY5XEi5/Z9HAHQEOEZk4vkXfVTmp2s0+HFDOxBHowSOImYbkALbbY6tHp9U/xyAyE/N92kA2Dn8sIM7UcurVc9cSTMBM5VttTwXDrX692I3uwmwsR61aGjc1U6NFulWdg6x+QBCpKc2mFGW6GWUD6Ea07OLRD7gHjUoRChmwNQWSQAaSNcGSzFBaxkn8Se59Oot/V3e/fCth9x6BonoUsNV5SqQ5WOw2g6C9VKhDKzkwH1Jkk1jRVj2ZDDYuVJJCytQQBzRZwB0CtzTelKDzDBbbBzA3ax2+7StesbEOq2VPQDyJ0rKEhHDszQTLNOLRSu595oUy9WIrn3T/m0PF2lOTDJQzXlKs+pYIhw7oRg3giaEdFycHGWZusWeQ1fFEGnC+AQMbwRZRoKWBguUDy3Hc0s0ojzK9hXn6bOGy9aewYws/yxW/QzeFqn0AKZyYG8vZebDRdVtsVLuQyPnBi/UmS7+pGPazBDiRoYJUw+b/LEYCczFDotQYqo1lJanKzQKT0jGzrMms3emG62B3Sz1aU3wNCnz0P4Tk3R1a02AFJ0z4EJumepSdPIoSkm6cCB+C4sGqLjze0hbQ1SsLJKJw9OSqPBAfs41MNxBHaxzbPIqdaoCLqG4ex2WHrynLTZWpWOuDAGRAiRL2vwlHvOgbYfII2mWE9HknnGx3SM8pflUubCInhVqYTL7UDxsbK7lstZFiVWDtrO9pBCbWlOuGCo5JaUmQlhC5Zym56ZWkfu44aqfXpZpMoYfncwDuF+Unrk+CzNozBwV5n3RthJ+J4NbZfStTZSwiCgGu5x35F5pARPxhXjesMg4wpELn5WmZqmpgdf32uh0JUAls1B1qa69OhMjjwaURYOyZ9e0vWlE9f2DKDtcP9H5XkqpJakv7MzpvTOxku5QvrH9lBYWujS1plkis9YZlWz2PQR5ZOF2TyHCHeaHjkNs98h/lfOx4Sy9WjjMdmEvpw0HXIW0TFSAJtXFtdjFJSX1zrUQ5FqVBy6C8BPo4BxcyLiJm5uxs1mTo6aVOA6wFY+ISGb6tocL5G96TKSiP09okSaGX4znDz28JNTJz/6hWzUfX7vRYRleRo2iCft+mYjvSweghdbJtFuZLYhrbcaAYpzB4exY/yvcRmadpsc0tDMzKY7H/XgVpUIZkdymF2D5ICEYtfPE+VHCIZmcSqdbFblyLy01h5SG8DVPUVnD05D6XgG4My4FV40WySk6UPKwkmcYiHgk2Owz4ajseCMOA8qbnPFY5FFTmOu7c8t/evUfT/3RaSiHypMXvaw9wpgUZm9krv1NTsaLVtBh8stWaC9Zomhdw5M5CItLKsM3dKhKHOaTRLwboembLQKUJrbXMpYtfJYiBHO5iDRTkdYPoFFcHkBa03yyw6LQnFbW10njSp/DPquhuodxxmFSSqdHz4MwtJEjpjseHJZfNO4TUd9ShGarBa0KEiIcT4uhz8I1S1r+sDXmkfPfiVZe/VZy/Ekp/O96N0c7cgbS/+Q1FsvwfX+ou14n7DD7gNO3Ntnw/4ogCmVi72y7ZicUZR9wfKohrJND9AqGaB3rS5rOm2OgnAF5/czk7VpYZm2kSWvy7ENnvTOJjEfRuIFxJurriVbpNxzHAax3FuOhRR6NyRlEXlM4q/FfMvi58i7Dh/S5EPxw47R6TPL28qr/uPEyUf/EtryhZxPy5Z90HfqDd7aCyO2rDQ4n3gT5/OFU3+iOteOQ6acsLPwcW+0dhbPj1nhqM4tKz7zzIeMZI2V6ZjwoUoGR3yqKsrjNKpsWZd7vpb6iU1sZg40kZFOhRyKKRs4hWmMcr7iKs52jc/GgGFcXW3uBJWg8R6zOQ+zc66pMLnVKnOyUcZymp8bBNX5lRv+4uG/Qkn+KnL0xWzcw9g904F6zw1VXj1mVjLKVR5fSqdXLuVF8XUVbDeihRPHwLZzbrD9sB20PuLk0d3I/I5UXd4SlLxjGpFlR8oUbxbj2tqFTJf7sEZ27Wz6KPOZUkoU5c6Z3vFE5e6ftbORVfa7zLdvLQiDJ43bcnuSw1C2GZCKqDpzqXn2vr+13crfIwKuxb22FDmrpj6Alv7OdHEDOaZbJKPUrryg/PoLmS6+FM3fM6mS4WP+1ssPIMQ+peLh3U4ezql8UGEWKtm5YxS4K+OYs4fasJU3ncpjVOYwpt5RQ+ZMjEn9lqn2ZE4l8LO9e4BcleqATI+xMNeVwqGNn85RUSkNhNHuwqnzlaOPfDHPoid1PG4xmzUXDrr9X7i8jbMxSibLwhkMxWzcHmj/DYzwG8H+j/4hwn3Foex+Ffc/befZ43baW7GLbNYqArC5kI0qPkRZcHLXOydgrFIWmTOCOzzi91lcaJQRuOUJzF3W6Z3Dktp8mgHmrU7ZVkghRYbbpHE/f+XeF5yZlb+uHLz3ywAN8RsZWbVzrZ/qIXNd7FZmCXldXLeS4fV89q6vh9W5hn/jhyetojiLqvGImwUPWUnvATsbKHPkVsuJfT7/bA5qZiUY9i5oklu1qdSyB2IZyyXOiPc5ykWQnUAUpALVtQj7Aq5/5KHveUt3f9VdOPQ3WX9zUEAUS6P4ffwV3w/mF204iYGhKEQjq0ifRUg+GzeWvkKUzBXe3Sd1nn3K6139iJ3Hj9jZaNkONyV5a4S7Yl0mvlST2TOyS6NjvCkDbO/kSN4uyI1fF2EMgLigeQfPfs9dPvsle3b5n5Hn+ml7VfZfPojfG/k/+lUvLU0HKxu2iulDrcxtPIXi46XW7GJh+6e9PPikirqfdJLhgyrPala5ia88TxqrIo/KHbgfPwIqRQT5jQ9vkt8o1MTi0xMnHvm8ml75lnaqXa60Ks9u79D8hwvAH//dAeROCngrM0Glvp57jetF4f5bOnfKh9A77W8+zyB+iqLhgy7YCUk1YfNnPFf2n3XpHHQSSh7LnXrkLiw/5ew78eUsy560qo1IZCXAey/F4cMJ4C2YKXmT94Uti3+l6zwq/flsYvnLyWS14ubBo9bw+kMI1EfsqPeAE3YPO5W6xQUs0yqpHHnwm97Uob9QjvoXOVjIVZVPJBQ50bvcIP8ZBfC/HSfZsYliA/MIQH0boH47nDuNfBofdbLR77jdK7/l1NNZd/bMH3sHz/xZMWjxWUWzr/JT+P8fPjwAvj1JTR5z/DcKr/LdvL9Wg1ifs/za09z01Zp+qr/Aqe78rx3v7evOr/zfAfAOgHcAvAPgna/b/vovAQYA0V2g0KMuwb8AAAAASUVORK5CYII=" />';
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') && @constant('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS') == '1') ? true : false;      
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Enable AddOn', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_STATUS', '-1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_COST', '2.50', 'The shipping cost will be multiplied by the number of items in an order that uses this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_HANDLING', '0', 'Handling fee for this shipping method.', '6', '0', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'lc_cfg_use_get_tax_class_title', 'lc_cfg_set_tax_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'lc_cfg_use_get_zone_class_title', 'lc_cfg_set_zone_classes_pull_down_menu(class=\"select\",', now())");
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'ADDONS_SHIPPING_" . strtoupper($this->_code) . "_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
   
  }
 /**
  * Return the configuration parameter keys an an array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_SHIPPING_' . strtoupper($this->_code) . '_STATUS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_COST',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_HANDLING',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_TAX_CLASS',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_ZONE',
                           'ADDONS_SHIPPING_' . strtoupper($this->_code) . '_SORT_ORDER');      
    }

    return $this->_keys;
  }    
}
?>