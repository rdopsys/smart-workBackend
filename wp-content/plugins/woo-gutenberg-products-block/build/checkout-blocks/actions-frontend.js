(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[36],{149:function(e,t,n){"use strict";var c=n(0);n(216),t.a=()=>Object(c.createElement)("span",{className:"wc-block-components-spinner","aria-hidden":"true"})},216:function(e,t){},281:function(e,t,n){"use strict";var c=n(13),a=n.n(c),s=n(0),o=n(71),r=n(6),i=n.n(r),l=n(149);n(282),t.a=e=>{let{className:t,showSpinner:n=!1,children:c,variant:r="contained",...u}=e;const b=i()("wc-block-components-button","wp-element-button",t,r,{"wc-block-components-button--loading":n});return Object(s.createElement)(o.a,a()({className:b},u),n&&Object(s.createElement)(l.a,null),Object(s.createElement)("span",{className:"wc-block-components-button__text"},c))}},282:function(e,t){},30:function(e,t,n){"use strict";n.d(t,"a",(function(){return o}));var c=n(0),a=n(14),s=n.n(a);function o(e){const t=Object(c.useRef)(e);return s()(e,t.current)||(t.current=e),t.current}},309:function(e,t,n){"use strict";n.d(t,"b",(function(){return i})),n.d(t,"a",(function(){return l}));var c=n(30),a=n(18),s=n(7),o=n(3);const r=function(){let e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];const{paymentMethodsInitialized:t,expressPaymentMethodsInitialized:n,availablePaymentMethods:r,availableExpressPaymentMethods:i}=Object(s.useSelect)(e=>{const t=e(o.PAYMENT_STORE_KEY);return{paymentMethodsInitialized:t.paymentMethodsInitialized(),expressPaymentMethodsInitialized:t.expressPaymentMethodsInitialized(),availableExpressPaymentMethods:t.getAvailableExpressPaymentMethods(),availablePaymentMethods:t.getAvailablePaymentMethods()}}),l=Object.values(r).map(e=>{let{name:t}=e;return t}),u=Object.values(i).map(e=>{let{name:t}=e;return t}),b=Object(a.getPaymentMethods)(),d=Object(a.getExpressPaymentMethods)(),m=Object.keys(b).reduce((e,t)=>(l.includes(t)&&(e[t]=b[t]),e),{}),p=Object.keys(d).reduce((e,t)=>(u.includes(t)&&(e[t]=d[t]),e),{}),O=Object(c.a)(m),h=Object(c.a)(p);return{paymentMethods:e?h:O,isInitialized:e?n:t}},i=()=>r(!1),l=()=>r(!0)},419:function(e,t,n){"use strict";var c=n(0),a=n(12);const s=Object(c.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(c.createElement)(a.Path,{d:"M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"}));t.a=s},447:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var c=n(3),a=n(7),s=n(87),o=n(309);const r=()=>{const{isCalculating:e,isBeforeProcessing:t,isProcessing:n,isAfterProcessing:r,isComplete:i,hasError:l}=Object(a.useSelect)(e=>{const t=e(c.CHECKOUT_STORE_KEY);return{isCalculating:t.isCalculating(),isBeforeProcessing:t.isBeforeProcessing(),isProcessing:t.isProcessing(),isAfterProcessing:t.isAfterProcessing(),isComplete:t.isComplete(),hasError:t.hasError()}}),{activePaymentMethod:u,isExpressPaymentMethodActive:b}=Object(a.useSelect)(e=>{const t=e(c.PAYMENT_STORE_KEY);return{activePaymentMethod:t.getActivePaymentMethod(),isExpressPaymentMethodActive:t.isExpressPaymentMethodActive()}}),{onSubmit:d}=Object(s.b)(),{paymentMethods:m={}}=Object(o.b)(),p=n||r||t,O=i&&!l;return{paymentMethodButtonLabel:(m[u]||{}).placeOrderButtonLabel,onSubmit:d,isCalculating:e,isDisabled:n||b,waitingForProcessing:p,waitingForRedirect:O}}},448:function(e,t){},449:function(e,t){},493:function(e,t,n){"use strict";n.r(t);var c=n(135),a=n(0),s=n(6),o=n.n(s),r=n(2),i=n(1),l=n(34),u=n(75),b=n(12),d=Object(a.createElement)(b.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(a.createElement)(b.Path,{d:"M20 10.8H6.7l4.1-4.5-1.1-1.1-5.8 6.3 5.8 5.8 1.1-1.1-4-3.9H20z"}));n(449);var m=e=>{let{link:t}=e;const n=t||l.c;return n?Object(a.createElement)("a",{href:n,className:"wc-block-components-checkout-return-to-cart-button"},Object(a.createElement)(u.a,{icon:d}),Object(i.__)("Return to Cart","woo-gutenberg-products-block")):null},p=n(447),O=n(419),h=n(281),g=e=>{let{label:t}=e;const{onSubmit:n,isCalculating:c,isDisabled:s,waitingForProcessing:o,waitingForRedirect:r}=Object(p.a)();return Object(a.createElement)(h.a,{className:"wc-block-components-checkout-place-order-button",onClick:n,disabled:c||s||o||r,showSpinner:o},r?Object(a.createElement)(u.a,{icon:O.a}):t)},j=n(47),w=n(11);n(448);const E=Object(i.__)("Place Order","woo-gutenberg-products-block");var P={cartPageId:{type:"number",default:0},showReturnToCart:{type:"boolean",default:!0},className:{type:"string",default:""},lock:{type:"object",default:{move:!0,remove:!0}},placeOrderButtonLabel:{type:"string",default:E}};t.default=Object(c.withFilteredAttributes)(P)(e=>{let{cartPageId:t,showReturnToCart:n,className:c,placeOrderButtonLabel:s}=e;const{paymentMethodButtonLabel:i}=Object(p.a)(),l=Object(w.applyCheckoutFilter)({filterName:"placeOrderButtonLabel",defaultValue:i||s||E});return Object(a.createElement)("div",{className:o()("wc-block-checkout__actions",c)},Object(a.createElement)(w.StoreNoticesContainer,{context:j.d.CHECKOUT_ACTIONS}),Object(a.createElement)("div",{className:"wc-block-checkout__actions_row"},n&&Object(a.createElement)(m,{link:Object(r.getSetting)("page-"+t,!1)}),Object(a.createElement)(g,{label:l})))})}}]);