/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/section-partnership/src/edit.js"
/*!************************************************!*\
  !*** ./blocks/section-partnership/src/edit.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Edit)\n/* harmony export */ });\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ \"@wordpress/block-editor\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/core-data */ \"@wordpress/core-data\");\n/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ \"react/jsx-runtime\");\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);\nfunction _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\n\n\n\n\n\n\nfunction Edit(_ref) {\n  var attributes = _ref.attributes,\n    setAttributes = _ref.setAttributes;\n  var eyebrow = attributes.eyebrow,\n    heading = attributes.heading,\n    subheading = attributes.subheading,\n    bgStyle = attributes.bgStyle;\n  var blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useBlockProps)({\n    className: \"wp-block-globeiron-section-partnership is-style-\".concat(bgStyle)\n  });\n\n  // ── Fetch published partners from the REST API ────────────────────────────\n  // logo_url is a registered REST field (see inc/post-types.php).\n  // meta._partner_url is exposed via register_post_meta show_in_rest: true.\n  var _useSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(function (select) {\n      var query = {\n        per_page: 100,\n        orderby: 'title',\n        order: 'asc',\n        status: 'publish'\n      };\n      var records = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__.store).getEntityRecords('postType', 'partner', query);\n      return {\n        partners: records !== null && records !== void 0 ? records : [],\n        isLoading: records === undefined\n      };\n    }, []),\n    partners = _useSelect.partners,\n    isLoading = _useSelect.isLoading;\n\n  // ── Preview ───────────────────────────────────────────────────────────────\n  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.Fragment, {\n    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.InspectorControls, {\n      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, {\n        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Section', 'globeiron'),\n        initialOpen: true,\n        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {\n          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Background Style', 'globeiron'),\n          value: bgStyle,\n          options: [{\n            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Dark', 'globeiron'),\n            value: 'dark'\n          }, {\n            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Light', 'globeiron'),\n            value: 'light'\n          }, {\n            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Brand', 'globeiron'),\n            value: 'brand'\n          }],\n          onChange: function onChange(val) {\n            return setAttributes({\n              bgStyle: val\n            });\n          }\n        })\n      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, {\n        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Partners', 'globeiron'),\n        initialOpen: false,\n        children: [isLoading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Spinner, {}), !isLoading && partners.length === 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Notice, {\n          status: \"warning\",\n          isDismissible: false,\n          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('No published partners found. Add some under Partners in the admin menu.', 'globeiron')\n        }), !isLoading && partners.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"p\", {\n          style: {\n            margin: 0,\n            fontSize: 12,\n            color: '#757575'\n          },\n          children: partners.length === 1 ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('1 partner found. Manage them under Partners in the admin menu.', 'globeiron') : \"\".concat(partners.length, \" \").concat((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('partners found. Manage them under Partners in the admin menu.', 'globeiron'))\n        })]\n      })]\n    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"section\", _objectSpread(_objectSpread({}, blockProps), {}, {\n      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(\"div\", {\n        className: \"partners__inner\",\n        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(\"header\", {\n          className: \"partners__header\",\n          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {\n            tagName: \"p\",\n            className: \"partners__eyebrow\",\n            placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Eyebrow text…', 'globeiron'),\n            value: eyebrow,\n            onChange: function onChange(val) {\n              return setAttributes({\n                eyebrow: val\n              });\n            },\n            allowedFormats: []\n          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {\n            tagName: \"h2\",\n            className: \"partners__heading\",\n            placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Section heading…', 'globeiron'),\n            value: heading,\n            onChange: function onChange(val) {\n              return setAttributes({\n                heading: val\n              });\n            },\n            allowedFormats: []\n          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {\n            tagName: \"p\",\n            className: \"partners__subheading\",\n            placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Supporting line…', 'globeiron'),\n            value: subheading,\n            onChange: function onChange(val) {\n              return setAttributes({\n                subheading: val\n              });\n            },\n            allowedFormats: ['core/bold', 'core/italic']\n          })]\n        }), isLoading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"div\", {\n          style: {\n            textAlign: 'center',\n            padding: '2rem'\n          },\n          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Spinner, {})\n        }), !isLoading && partners.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"div\", {\n          className: \"partners__marquee\",\n          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"ul\", {\n            className: \"partners__track\",\n            role: \"list\",\n            children: partners.map(function (partner) {\n              var _partner$title$render, _partner$title, _partner$logo_url;\n              var name = (_partner$title$render = (_partner$title = partner.title) === null || _partner$title === void 0 ? void 0 : _partner$title.rendered) !== null && _partner$title$render !== void 0 ? _partner$title$render : '';\n              var logoUrl = (_partner$logo_url = partner.logo_url) !== null && _partner$logo_url !== void 0 ? _partner$logo_url : '';\n              return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"li\", {\n                className: \"partners__tile\",\n                children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"div\", {\n                  className: \"partners__tile-inner\",\n                  children: logoUrl ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"img\", {\n                    src: logoUrl,\n                    alt: name,\n                    className: \"partners__logo\"\n                  }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"span\", {\n                    className: \"partners__logo-placeholder\",\n                    children: name\n                  })\n                })\n              }, partner.id);\n            })\n          })\n        }), !isLoading && partners.length === 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(\"p\", {\n          style: {\n            textAlign: 'center',\n            opacity: 0.5,\n            padding: '2rem 0',\n            fontStyle: 'italic'\n          },\n          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('No partners yet — add some under Partners in the admin.', 'globeiron')\n        })]\n      })\n    }))]\n  });\n}\n\n//# sourceURL=webpack://globeiron/./blocks/section-partnership/src/edit.js?\n}");

/***/ },

/***/ "./blocks/section-partnership/src/index.js"
/*!*************************************************!*\
  !*** ./blocks/section-partnership/src/index.js ***!
  \*************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ \"./blocks/section-partnership/src/edit.js\");\n/* harmony import */ var _save__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./save */ \"./blocks/section-partnership/src/save.js\");\n/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../block.json */ \"./blocks/section-partnership/block.json\");\nfunction _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }\nfunction _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }\nfunction _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\n\n\n\n\n(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, _objectSpread(_objectSpread({}, _block_json__WEBPACK_IMPORTED_MODULE_3__), {}, {\n  edit: _edit__WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  save: _save__WEBPACK_IMPORTED_MODULE_2__[\"default\"]\n}));\n\n//# sourceURL=webpack://globeiron/./blocks/section-partnership/src/index.js?\n}");

/***/ },

/***/ "./blocks/section-partnership/src/save.js"
/*!************************************************!*\
  !*** ./blocks/section-partnership/src/save.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Save)\n/* harmony export */ });\n/**\n * Dynamic block — frontend output is handled entirely by render.php.\n * save() must return null so WordPress knows not to store or validate\n * static HTML for this block.\n */\nfunction Save() {\n  return null;\n}\n\n//# sourceURL=webpack://globeiron/./blocks/section-partnership/src/save.js?\n}");

/***/ },

/***/ "react/jsx-runtime"
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
(module) {

module.exports = window["ReactJSXRuntime"];

/***/ },

/***/ "@wordpress/block-editor"
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
(module) {

module.exports = window["wp"]["blockEditor"];

/***/ },

/***/ "@wordpress/blocks"
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
(module) {

module.exports = window["wp"]["blocks"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["components"];

/***/ },

/***/ "@wordpress/core-data"
/*!**********************************!*\
  !*** external ["wp","coreData"] ***!
  \**********************************/
(module) {

module.exports = window["wp"]["coreData"];

/***/ },

/***/ "@wordpress/data"
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["data"];

/***/ },

/***/ "@wordpress/i18n"
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["i18n"];

/***/ },

/***/ "./blocks/section-partnership/block.json"
/*!***********************************************!*\
  !*** ./blocks/section-partnership/block.json ***!
  \***********************************************/
(module) {

eval("{module.exports = /*#__PURE__*/JSON.parse('{\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":3,\"name\":\"globeiron/section-partnership\",\"version\":\"1.0.0\",\"title\":\"Section — Partnership\",\"category\":\"globeiron\",\"icon\":\"groups\",\"description\":\"Bold partner logo grid — pulls from the Partner post type automatically.\",\"keywords\":[\"partners\",\"logos\",\"clients\",\"brands\",\"sponsors\"],\"textdomain\":\"globeiron\",\"editorScript\":\"file:./index.js\",\"render\":\"file:./render.php\",\"attributes\":{\"align\":{\"type\":\"string\",\"default\":\"full\"},\"eyebrow\":{\"type\":\"string\",\"default\":\"Our Partners\"},\"heading\":{\"type\":\"string\",\"default\":\"Trusted by Industry Leaders\"},\"subheading\":{\"type\":\"string\",\"default\":\"We work alongside the best suppliers and brands in the business.\"},\"bgStyle\":{\"type\":\"string\",\"default\":\"dark\"}},\"supports\":{\"html\":false,\"align\":[\"full\"],\"anchor\":true,\"customClassName\":true}}');\n\n//# sourceURL=webpack://globeiron/./blocks/section-partnership/block.json?\n}");

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./blocks/section-partnership/src/index.js");
/******/ 	
/******/ })()
;