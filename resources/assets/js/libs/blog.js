// var Shuffle = window.Shuffle;
var element_1 = document.querySelector('.my-shuffle-container-1');
var element_2 = document.querySelector('.my-shuffle-container-2');
var element_3 = document.querySelector('.my-shuffle-container-3');

if (element_1) {
  var sizer1 = element_1.querySelector('.my-sizer-element-1');
  var sizer2 = element_2.querySelector('.my-sizer-element-2');
  var sizer3 = element_3.querySelector('.my-sizer-element-3');

  var shuffleInstance1 = new Shuffle(element_1, {
    itemSelector: '.picture-item-1',
    sizer: sizer1 // could also be a selector: '.my-sizer-element'
  });

  var shuffleInstance2 = new Shuffle(element_2, {
    itemSelector: '.picture-item-2',
    sizer: sizer2 // could also be a selector: '.my-sizer-element'
  });

  var shuffleInstance3 = new Shuffle(element_3, {
    itemSelector: '.picture-item-3',
    sizer: sizer3 // could also be a selector: '.my-sizer-element'
  });
}
