							<section id="presentazione" class="main">
								<div class="spotlight">
									<div class="content">
                                                                            <?php
                                                                            $txt="";
                                                                            if(Pageloader::getData(0)!==false){	//c'è un messaggio da comunicare
                                                                                    $m_code=Pageloader::getData(0);
                                                                                    $mess=Notification::showCode($m_code,NULL,true);
                                                                                    if($mess){
                                                                                            $txt=$mess['description'];
                                                                                    }
                                                                                    else
                                                                                            $txt="<p>Unknown Message</p>";

                                                                            }
                                                                            echo $txt;
                                                                            ?>
									</div>
									
								</div>
							</section>