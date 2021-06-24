$(document).ready(function () {
//customise scrollbar
    $('.scrollbar-dynamic').scrollbar();

//smooth scroll
    $('a').on('click', function () {
        let thisItem = $(this);
        let dest = thisItem.attr('href');
        if (dest !== undefined && dest !== '') {
            $('html').animate({
                    scrollTop: $(dest).offset().top
                }, 500
            );
        }
        return false;
    });

//typing text
    let wrapperText = '.typing-wrapper'
    let colorText = '.typing-color-text'
    let textRegular = '.typing-text'

    titleList(wrapperText, textRegular, colorText, 'primary-color', window.innerHeight / 2)

    function titleList(wrapper, text, colorText, colorStyle, heightRun) {
        $(wrapper).each(function () {
            let thisItem = $(this);
            if (thisItem.offset().top < $(window).scrollTop() + heightRun && !thisItem.hasClass('typing')) {
                titleColorText(thisItem.find(colorText)[0], thisItem.find(text)[0], thisItem[0], colorStyle);
                thisItem.addClass('typing')
            }
        })
    }

    function titleColorText(colorText, text, wrapperText, colorStyle) {
        let textCont = colorText.textContent + text.textContent;
        for (let i = 0; i < textCont.length; i++) {
            (function (i) {
                setTimeout(function () {
                    let texts = document.createTextNode(textCont[i])
                    let span = document.createElement('span');
                    span.appendChild(texts);
                    if (i < colorText.textContent.length) span.classList.add(colorStyle);
                    wrapperText.appendChild(span);
                }, 50 * i);
            }(i));
        }
    }

//header
    let headerLogo = document.querySelector('.header-logo-move');
    let header = document.querySelector('.header-move');

    function showHeaderLogo() {
        if ($(window).scrollTop() > 0) {
            headerLogo.classList.remove('header__logo-image-wrapper--large')
            header.classList.add('header--bg')
            headerLogo.classList.remove('move')
        } else {
            header.classList.remove('header--bg')
            if (!$('.slick-active').index('.hero-slick__slide')) {
                headerLogo.classList.add('header__logo-image-wrapper--large')
            }
        }
    }
    showHeaderLogo();

//hero
    $('.hero-slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplaySpeed: 5000,
        dots: true,
        infinite: true,
        speed: 100,
        fade: true,
        cssEase: 'linear',
        pauseOnHover: false
    });

    let heroSlickWrapper = document.querySelectorAll('.typing-hero-wrapper');
    let heroSlickText = document.querySelectorAll('.typing-hero-text');
    let heroLearnMore = $('.hero-slick__btn'),
        heroPlay = $('.hero-slick__play'),
        heroSubTitle = $('.hero-slick__text'),
        heroTitle = $('.hero-slick__title')

    titleText(heroSlickText[0], heroSlickWrapper[0])

    function heroShowContent() {
        heroLearnMore.removeClass('opc-1 opc-3')
        heroPlay.removeClass('opc-1 opc-3')
        heroSubTitle.removeClass('opc-2')
        heroTitle.removeClass('opc-2')
        setTimeout(function () {
            heroLearnMore.removeClass('tr-07s')
            heroPlay.removeClass('tr-07s')
            heroSubTitle.removeClass('tr-07s')
            heroTitle.removeClass('tr-07s')
        }, 750)
    }

    setTimeout(function () {
        $('.hero-slick').slick('slickPlay');
    }, 1200)
    setTimeout(function () {
        heroShowContent()
    }, 2700)

    $('.hero-slick').on('afterChange', function (event, slick, currentSlide) {
        heroShowContent()
    });
    $('.hero-slick').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        heroLearnMore.addClass('opc-1')
        heroPlay.addClass('opc-1')
        heroSubTitle.addClass('opc-2')
        heroTitle.addClass('opc-2')
        setTimeout(function () {
            heroLearnMore.addClass('tr-07s')
            heroPlay.addClass('tr-07s')
            heroSubTitle.addClass('tr-07s')
            heroTitle.addClass('tr-07s')
        }, 50)
        if (nextSlide) {
            headerLogo.classList.remove('header__logo-image-wrapper--large')
        } else {
            if ($(window).scrollTop() <= 0) {
                headerLogo.classList.add('header__logo-image-wrapper--large')
            }
        }
    });

    let heroVideo = document.getElementById('hero-video')
    $('.hero-slick__play').on('click', function () {
        $('body').addClass('hidden')
        $('.hero-video').fadeIn(500);
        heroVideo.play();
    })
    $('.hero-video__close').on('click', function () {
        $('body').removeClass('hidden')
        $('.hero-video').fadeOut(500)
        heroVideo.pause();
        heroVideo.currentTime = 0;
    })

    function titleText(textList, wrapperText) {
        let textCont = textList.textContent;
        let textLength = textCont.length
        for (let i = 0; i < textLength; i++) {
            (function (i) {
                setTimeout(function () {

                    let texts = document.createTextNode(textCont[i])
                    let span = document.createElement('span');
                    span.appendChild(texts);
                    span.classList.add('added')
                    wrapperText.appendChild(span);
                }, 40 * i);
            }(i));
        }
    }

//dictionary (Institutional - Level Analytics for the Retail Trader block)
    let dictionaryWrapper = '.dictionary__title-wrapper'

    function fliper(dictionaryWrapper) {
        let heightTop = window.innerHeight / 2 - 50
        let dictionary = $(dictionaryWrapper)
        if (dictionary.offset().top < $(window).scrollTop() + heightTop && !dictionary.hasClass('flips')) {
            setTimeout(function () {
                dictionary.find('.dictionary__title-content').toggleClass('d-none')
                setInterval(function () {
                    dictionary.find('.step-1').toggleClass('flip');
                }, 2000)

                setTimeout(function () {
                    setInterval(function () {
                        dictionary.find('.step-2').toggleClass('flip');
                    }, 2000)
                }, 150)

                setTimeout(function () {
                    setInterval(function () {
                        dictionary.find('.step-3').toggleClass('flip');
                    }, 2000)
                }, 300)
            }, 2500)
            $(dictionaryWrapper).addClass('flips')
        }
    }
    fliper(dictionaryWrapper);

    $('.header__btn--collapse').on('click', function () {
        $('.header-nav').fadeIn('400', function () {
            let navLink = $('.header-nav__item')
            $('.header-nav__wrapper').removeClass('move')
            setTimeout(function () {
                $(navLink).each(function () {
                    let thisItem = $(this);
                    let i = thisItem.index()
                    setTimeout(function () {
                        thisItem.removeClass('move')
                    }, i * 80)
                })
            }, 400)
        })
    })
    $('.header-nav__close').on('click', function () {
        headerNavClose()
    })
    $('.nav-link-close').on('click', function () {
        headerNavClose()
    })
    $('.header-nav__bg').on('click', function () {
        headerNavClose()
    })

    function headerNavClose() {
        $('.header-nav__wrapper').addClass('move');
        setTimeout(function () {
            $('.header-nav').fadeOut('200', function () {
                $('.header-nav__item').addClass('move')
            });
        }, 400)
    }

    if ($(window).scrollTop() <= 0) {
        headerLogo.classList.add('header__logo-image-wrapper--large')
        headerLogo.classList.add('move')
        setTimeout(function () {
            headerLogo.classList.remove('d-none')
            headerLogo.classList.add('tr-07s')
        }, 10)
        setTimeout(function () {
            headerLogo.classList.remove('move')
        }, 2500)
    } else {
        headerLogo.classList.remove('d-none')
        headerLogo.classList.add('tr-04s')
    }

//Data Mining
    function cirleMoution() {
        if (window.innerWidth > 768) {
            if ($('.data-mining').offset().top <= $(window).scrollTop() + window.innerHeight / 2 - 100) {
                $('.data-mining__item').each(function () {
                    let i = $(this).index();
                    let thisItem = $(this);
                    setTimeout(function () {
                        thisItem.removeClass("data-mining__item--top data-mining__item--l1 data-mining__item--l2 data-mining__item--l3")
                        setTimeout(function () {
                            thisItem.find('.data-mining__item-text').removeClass('data-mining__item-text--op-0')
                        }, 500)

                    }, (i - 2) * 500)

                })
            }
        } else {
            $('.data-mining__item').each(function () {
                let thisItem = $(this);
                if (thisItem.offset().top < $(window).scrollTop() + window.innerHeight / 2 + 100) {
                    thisItem.removeClass("data-mining__item--top data-mining__item--l1 data-mining__item--l2 data-mining__item--l3")
                    setTimeout(function () {
                        thisItem.find('.data-mining__item-text').removeClass('data-mining__item-text--op-0')
                    }, 500)
                }
            })
        }
    }
    cirleMoution()

    $('.data-mining__item').on('click', function () {
        if (window.innerWidth < 769) {
            $('body').toggleClass('hidden');
            $(this).find('.data-mining__hover').fadeToggle(800);
            $(this).find('.data-mining__hover-wrapper').fadeToggle(800);
        }
    })

//Scanners - Indicators - Analytix
    let scannersCard = '.scanners__card'
    let scannersForm = '.scanners__form-wrapper'

    function showCards(scannersCard, heightRun) {
        $(scannersCard).each(function () {
            let thisItem = $(this);
            if (thisItem.offset().top < $(window).scrollTop() + heightRun && !thisItem.hasClass('active')) {
                let scannersCardCol = thisItem.find('.scanners__card-col'),
                    wrapperTextSubtitle = thisItem.find('.typing-subtitle-wrapper'),
                    colorTextSubtitle = thisItem.find('.typing-subtitle-color-text'),
                    textRegularSubtitle = thisItem.find('.typing-subtitle-text')
                scannersCardCol[0].classList.remove('scanners__card-col--l')
                scannersCardCol[1].classList.remove('scanners__card-col--r')
                thisItem.addClass('active')

                setTimeout(function () {
                    titleColorText(colorTextSubtitle[0], textRegularSubtitle[0], wrapperTextSubtitle[0], 'primary-color')
                    thisItem.removeClass('hidden')
                }, 650)
            }
        })
    }

    function showForm(scannersForm, heightRun) {
        if ($(scannersForm).offset().top < $(window).scrollTop() + heightRun) {
            $(scannersForm).removeClass('scanners__form-wrapper--move')

        }
    }

    let scannersArrow = $('.scanners__card .arrow-link');

    scannersArrow.on('click', function () {
        $('.scanners__ul .d-none').slideDown(600)
        $(this).fadeOut(200)
    })

    scrollDownShow(scannersArrow, '.scanners__card ', '.scanners__ul .d-none')

    function scrollDownShow(linkBtn, parentName, fadeOutClass) {
        let thisBlock = linkBtn.closest(parentName),
            nextBlock = thisBlock.next();
        if (nextBlock.offset().top < $(window).scrollTop() || thisBlock.offset().top - window.innerHeight > $(window).scrollTop()) {
            $(fadeOutClass).fadeOut(100, function () {
                linkBtn.fadeIn(100)
            })
        }
    }

    //block-list
    $('.block-list__item-header').click(function () {
        console.log()
        let thisItems = $(this),
            itemHeader = $('.block-list__item-header'),
            itemBody = '.block-list__item-body',
            time = 400;

        if (thisItems.hasClass('active')) {
            thisItems.parent().find(itemBody).slideUp(time);
            thisItems.removeClass('active')
        } else {
            itemHeader.parent().find(itemBody).slideUp(time);
            thisItems.addClass('active');
            itemHeader.not(thisItems).removeClass('active');
            setTimeout(function () {
                if ($(window).scrollTop() > thisItems.parent().offset().top) {
                    $('html').animate({scrollTop: thisItems.parent().offset().top - 60}, 200)
                }
                thisItems.parent().find(itemBody).slideDown(time);
            }, time)
        }
    })

//Powered by Data, Filtered and Curated by Humans
    let topBalls = '.powered__top-balls'

    function poweredShowText() {
        if ($('.powered__text-wrapper').offset().top < $(window).scrollTop() + window.innerHeight / 2) {
            poweredTextMove()
        }

    }

    function poweredTextMove() {
        $('.powered__text-wrapper').find('.move').each(function () {
            let i = $(this).index();
            let thisItem = $(this);
            if (thisItem.parent().hasClass('move')) {
                setTimeout(function () {
                    if (thisItem.hasClass('wait')) {
                        setTimeout(function () {
                            thisItem.removeClass('powered__text-move')
                        }, 5000)
                    } else thisItem.removeClass('powered__text-move')


                }, i * 400)
            }
        })
    }

    function poweredTextUnmove() {
        $('.powered__text-wrapper').find('.move').each(function () {
            let i = $(this).index();
            let thisItem = $(this);
            setTimeout(function () {
                thisItem.addClass('powered__text-move')
            }, i * 400)
        })
        setTimeout(function () {
            $('.powered__bottom-ball').each(function () {
                let i = $(this).index();
                let thisItem = $(this);
                setTimeout(function () {
                    thisItem.removeClass('move-ball')
                }, i * 400)
            })
        }, 1200)
    }

    function poweredBallsMove(balls, ball, heightTop) {
        if ($(balls).offset().top < $(window).scrollTop() + heightTop) {
            $(ball).each(function () {
                let i = $(this).index();
                let thisItem = $(this);
                setTimeout(function () {
                    thisItem.removeClass('move-ball')
                }, i * 400)
            })

            poweredTextMove()
        }
    }

    $('.powered .arrow-link').on('click', function () {
        poweredTextUnmove()
        setTimeout(function () {
            $('.powered__text-wrapper').toggleClass('move')
            setTimeout(function () {
                poweredTextMove()
            }, 200)
        }, 1200)
    })

//Intelligent, Event-Driven Feeds
    function showIntelligentCards() {
        if (window.innerWidth > 768) {
            if ($('.intelligent__cards').offset().top <= $(window).scrollTop() + window.innerHeight / 2 + 158) {

                $('.intelligent__card-wrapper').removeClass('intelligent__card-wrapper--c2')
                setTimeout(function () {
                    $('.intelligent__card-wrapper').removeClass('intelligent__card-wrapper--c1 intelligent__card-wrapper--c3')
                }, 800)

                $('.intelligent__text').removeClass('move')
            }
        } else {
            $('.intelligent__card-wrapper').each(function () {
                let thisItem = $(this);
                if (thisItem.offset().top < $(window).scrollTop() + window.innerHeight / 2 + 100) {
                    $('.intelligent__text').removeClass('move')
                    thisItem.removeClass('intelligent__card-wrapper--c1 intelligent__card-wrapper--c2 intelligent__card-wrapper--c3')
                }
            })
        }

    }

    $('.intelligent__card-more').on('click', function () {
        $(this).closest('.intelligent__card-wrapper').find('.intelligent__card-about').fadeToggle(600)
        $(this).toggleClass('dark')
        if ($(this).text() === 'Hide') {
            $(this).text('Learn More')
        } else {
            $(this).text('Hide')
        }
    })


    function linkIntelligentShow(linkBtn, parentName, fadeOutClass) {
        let thisBlock = linkBtn.closest(parentName),
            nextBlock = thisBlock.next();
        if (nextBlock.offset().top < $(window).scrollTop() || thisBlock.offset().top - window.innerHeight > $(window).scrollTop()) {
            $(fadeOutClass).fadeOut(100, function () {
                linkBtn.removeClass('dark')
                linkBtn.text('Learn More')
            })
        }
    }

//Comments
    $('.comments-slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplaySpeed: 5000,
        dots: true,
        infinite: true,
        speed: 800,
        fade: true,
        cssEase: 'linear',
    });

    function slickStartPosition() {
        $('.comments__slide-text-wrapper').addClass('tr-none')
        $('.comments-slick .slick-arrow').addClass('tr-none')
        setTimeout(function () {
            $('.comments__slide-text-wrapper').addClass('op-0')
            $('.comments-slick .slick-arrow').addClass('move')
        }, 50)
        setTimeout(function () {
            $('.comments__slide-text-wrapper').removeClass('tr-none')
            $('.comments-slick .slick-arrow').removeClass('tr-none')
        }, 100)
    }

    slickStartPosition()

    function slickStart() {
        if ($('.comments').offset().top < $(window).scrollTop() + window.innerHeight / 2 - 100 && !$('.comments').hasClass('slick-start')) {
            setTimeout(function () {
                setTimeout(function () {
                    $('.comments__slide-text-wrapper').removeClass('op-0')

                }, 600)
                $('.comments-slick .slick-arrow').removeClass('move')
                $('.comments-slick').slick('slickPlay');
                $('.comments').addClass('slick-start')
            }, 200)
        }
    }

//Designed and Developed for Traders block
    function designedShow() {
        let designedCard = $('.designed__content')
        $(designedCard).each(function () {
            let thisItem = $(this);
            if (thisItem.offset().top < $(window).scrollTop() + window.innerHeight / 2 + 100 && !thisItem.hasClass('active')) {
                let scannersCardCol = thisItem.find('.designed__content-col');
                scannersCardCol[0].classList.remove('designed__content-col--r')
                scannersCardCol[1].classList.remove('designed__content-col--l')
                thisItem.addClass('active')

                setTimeout(function () {
                    let bloks = designedCard.find($('.block-list__item'));
                    bloks.each(function () {
                        let thisItem = $(this);
                        let i = thisItem.index()
                        setTimeout(function () {
                            thisItem.removeClass('move')
                        }, i * 100)
                    })
                    setTimeout(function () {
                        thisItem.removeClass('hidden')
                    }, bloks.length * 100 + 500)
                }, 500)
            }
        })
    }
    designedShow()
    fliper('.designed__title-wrapper');

//Get Access (Noise)
    $('.noise__btn-wrapper').on('click', function () {
        $(this).fadeOut(500, function () {
            $('.noise__card').fadeIn(2500)
        })
    })
    $('.noise__card-btn-wrapper').on('click', function () {
        $('.noise__card').fadeOut(600, function () {
            $('.noise__btn-wrapper').fadeIn(1200)
        })
    })

    function noiseHide() {
        let noiseBlock = $('.noise'),
            footer = $('.footer');
        if (footer.offset().top < $(window).scrollTop() || noiseBlock.offset().top - window.innerHeight > $(window).scrollTop()) {
            $('.noise__card').fadeOut(100, function () {
                $('.noise__btn-wrapper').fadeIn(100)
            })
        }
    }


//POP-UPs (all)
    function showModal(classOpen, nameModal) {
        $(classOpen).on('click', function () {
            setTimeout(function () {
                $(nameModal).fadeIn(200, function () {
                    $(this).find('.modal__wrapper').removeClass('move')
                    $('body').addClass('hidden')
                })
            }, 200)
        })
    }

    showModal('.story', '.modal-story')
    showModal('.incoming-information', '.modal-information')
    showModal('.analysis', '.modal-analysis')
    showModal('.refund', '.modal-refund')
    showModal('.privacy-policy', '.modal-privacy-policy')
    showModal('.trial', '.modal-trial')
    showModal('.login', '.modal-sign-in')
    showModal('.register', '.modal-register')

    $('.modal__close').on('click', function () {
        closeModal(this);
    })
    $('.modal-close').on('click', function () {
        closeModal(this);
    })
    $('.modal__bg').on('click', function () {
        closeModal(this)
    })

    function closeModal(modal) {
        let thisModal = $(modal).closest('.modal')
        thisModal.fadeOut(400, function () {
            $('body').removeClass('hidden')
        })
        thisModal.find('.modal__wrapper').addClass('move');
    }


//keypress
    $(document).keydown(function (e) {
        if (e.keyCode == 27) {
            closeModal();
            headerNavClose();
        }
    });

//scrolls
    window.addEventListener('scroll', function () {
        titleList(wrapperText, textRegular, colorText, 'primary-color', window.innerHeight / 2)
        $('.header__logo-image-wrapper').removeClass('header__logo-image-wrapper--large')

        showHeaderLogo()

        fliper(dictionaryWrapper);

        cirleMoution();

        fliper('.designed__title-wrapper');
        designedShow()

        showCards(scannersCard, window.innerHeight / 2 + 100);
        showForm(scannersForm, window.innerHeight / 2 + 200)

        scrollDownShow(scannersArrow, '.scanners__card ', '.scanners__ul .d-none')

        poweredShowText()
        poweredBallsMove(topBalls, '.powered__top-ball', window.innerHeight / 2)
        poweredBallsMove('.powered__bottom-balls', '.powered__bottom-ball', window.innerHeight - 100)

        showIntelligentCards()
        linkIntelligentShow($('.intelligent__card-more'), '.intelligent', '.intelligent__card-about')

        slickStart()

        noiseHide()
    })
});
