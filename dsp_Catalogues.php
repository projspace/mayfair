<link rel="stylesheet" href="<?= $config['layout_dir'] ?>css/tango/skin.css" />
<script type="text/javascript" src="<?= $config['layout_dir'] ?>js/jcarousel.min.js"></script>
<div id="content-wrapper" class="yui3-g">
    <div id="content" class="yui3-u  homeV3">
        <article id="index-slider">
            <section>
                <h3>Catalogues</h3>
                <ul id="slider_container"  class="jcarousel-skin-tango">
                    <li>
                        <div class="hoverBlock">
                            <img src="<?= $config['dir'] ?>images/catalogues/1.jpg" alt="2012 Dance shoes catalogue" title="2012 Dance shoes catalogue" />
                        </div>
                    </li>
                    <li>
                        <div class="hoverBlock">
                            <img src="<?= $config['dir'] ?>images/catalogues/2.jpg" alt="2012 Bloch Basics apparel catalogue" title="2012 Bloch Basics apparel catalogue" />
                        </div>
                    </li>
                    <li>
                        <div class="hoverBlock">
                            <img src="<?= $config['dir'] ?>images/catalogues/5.jpg" alt="Bloch Spring / Summer 2013 catalogue" title="Bloch Spring / Summer 2013 catalogue" />
                        </div>
                    </li>
                    <li>
                        <div class="hoverBlock">
                            <img src="<?= $config['dir'] ?>images/catalogues/3.jpg" alt="Bloch Holiday 2012 catalogue" title="Bloch Holiday 2012 catalogue" />
                        </div>
                    </li>
                    <li>
                        <div class="hoverBlock">
                            <img src="<?= $config['dir'] ?>images/catalogues/4.jpg" alt="Bloch Fall / Winter 2012 catalogue" title="Bloch Fall / Winter 2012 catalogue" />
                        </div>
                    </li>                    
                </ul>
            </section>
            <footer>
                <div id="box-for-text">
                    <div class="text-boxes" style="display: none;"><p><span>2012 Dance shoes catalogue</span><br/>Full range of Bloch Dance Shoes 2012</p></div>
                    <div class="text-boxes" style="display: none;"><p><span>2012 Bloch Basics apparel catalogue</span><br/>Full range of Bloch Apparel 2012</p></div>
                    <div class="text-boxes" style="display: none;"><p><span>Bloch Spring / Summer 2013 catalogue</span><br/>Full range of Bloch Spring / Summer 2013</p></div>
                    <div class="text-boxes" style="display: none;"><p><span>Bloch Holiday 2012 catalogue</span><br/>Full range of Bloch Holiday 2012</p></div>
                    <div class="text-boxes" style="display: none;"><p><span>Bloch Fall / Winter 2012 catalogue</span><br/>Full range of Bloch Fall / Winter 2012</p></div>
                    
                </div>
                <div id="front-form">
                    <p class="success"></p>     
            
                    <form action="index.php?fuseaction=home.catalogues&act=submit" method="post" id="subscribe-form">
                    	<p>Signup to download selected catalogue:</p>
                        <p class="error" style="color:#f00;"></p>
                        <? foreach($config['catalogues'] as $name => $pdf): ?>
                        <label class="radio">
                            <input type="radio" name="catalogue" value="<?= $name ?>" checked="checked" />
                            <?= $name ?>
                        </label><br />
                        <? endforeach ?>
                        <br />                        
                        <input class="gradbg" type="text" name="name" class="tick" placeholder="name" data-placeholder="name" id="name-field" /><br/>
                        <input class="gradbg" type="text" name="email" class="tick" placeholder="e-mail" data-placeholder="e-mail" id="email-field" />
                        <input type="submit" value="submit" id="submit-bt" />
                    </form>                    
                </div>
            </footer>
        </article>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#slider_container').jcarousel({
            scroll: 1
        });

        $('.hoverBlock').hover(function(){
            $('.text-boxes').eq($(this).parent().index()).toggleClass('block');
        });

        $('input.tick').each(function(){
            $(this).val($(this).data('placeholder'));
        })

        $('input.tick').focus(function(){
            var pl = $(this).data('placeholder');
            if($(this).val() == pl)
                $(this).val('');
        });

        $('input.tick').blur(function(){
            var pl = $(this).data('placeholder');
            if($(this).val() == '')
                $(this).val(pl);
        });

        $('#subscribe-form').submit(function(){
            $('p.error').hide();
            $('p.success').hide();
            $('#submit-bt').val('Please wait...');

            var name = $('#name-field').val();
            var email = $('#email-field').val();
            var catalogue = $('input[name=catalogue]:checked').val();

            if(!name || name == $('#name-field').data('placeholder')){
                triggerError('Please enter your name');
                return false;
            }

            if(!email || email == $('#email-field').data('placeholder')){
                triggerError('Please enter your email');
                return false;
            }

            if(!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)){
                triggerError('Please enter a valid email');
                return false;
            }

            if(!catalogue){
                triggerError('Please select a catalogue');
                return false;
            }

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
               // dataType: 'json',
                success: function(data){
                    if(data == 'OK'){
                        $('p.success').text('Thank you. Your catalogue has now been emailed to you.');
                        $('p.success').show();
                        $('form#subscribe-form').hide();

                        $('#submit-bt').val('Submit');
                        $('#name-field').val('');
                        $('#email-field').val('');
                    }else{
                        triggerError(data);
                    }
                }
            })

            return false;
        });

        function triggerError(msg){
            $('p.error').text(msg);
            $('p.error').show();

            $('#submit-bt').val('Submit');
        }
    });
</script>