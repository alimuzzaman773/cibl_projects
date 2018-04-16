/// Knockout Mapping plugin v2.3.3
/// (c) 2012 Steven Sanderson, Roy Jacobs - http://knockoutjs.com/
/// License: MIT (http://www.opensource.org/licenses/mit-license.php)
(function (e) { "function" === typeof require && "object" === typeof exports && "object" === typeof module ? e(require("knockout"), exports) : "function" === typeof define && define.amd ? define(["knockout", "exports"], e) : e(ko, ko.mapping = {}) })(function (e, f) {
    function x(b, c) {
        var a, d; for (d in c) if (c.hasOwnProperty(d) && c[d]) if (a = f.getType(b[d]), d && b[d] && "array" !== a && "string" !== a) x(b[d], c[d]); else if ("array" === f.getType(b[d]) && "array" === f.getType(c[d])) {
            a = b; for (var e = d, i = b[d], j = c[d], l = {}, g = i.length - 1; 0 <= g; --g) l[i[g]] = i[g]; for (g =
            j.length - 1; 0 <= g; --g) l[j[g]] = j[g]; i = []; j = void 0; for (j in l) i.push(l[j]); a[e] = i
        } else b[d] = c[d]
    } function E(b, c) { var a = {}; x(a, b); x(a, c); return a } function y(b, c) { for (var a = E({}, b), d = K.length - 1; 0 <= d; d--) { var e = K[d]; a[e] && (a[""] instanceof Object || (a[""] = {}), a[""][e] = a[e], delete a[e]) } c && (a.ignore = r(c.ignore, a.ignore), a.include = r(c.include, a.include), a.copy = r(c.copy, a.copy)); a.ignore = r(a.ignore, h.ignore); a.include = r(a.include, h.include); a.copy = r(a.copy, h.copy); a.mappedProperties = a.mappedProperties || {}; return a }
    function r(b, c) { "array" !== f.getType(b) && (b = "undefined" === f.getType(b) ? [] : [b]); "array" !== f.getType(c) && (c = "undefined" === f.getType(c) ? [] : [c]); return e.utils.arrayGetDistinctValues(b.concat(c)) } function F(b, c, a, d, D, i, j) {
        var l = "array" === f.getType(e.utils.unwrapObservable(c)), i = i || ""; if (f.isMapped(b)) var g = e.utils.unwrapObservable(b)[p], a = E(g, a); var q = j || D, h = function () { return a[d] && a[d].create instanceof Function }, r = function (b) {
            var f = G, g = e.dependentObservable; e.dependentObservable = function (a, b, c) {
                c = c ||
                {}; a && "object" == typeof a && (c = a); var d = c.deferEvaluation, L = !1; c.deferEvaluation = !0; a = new H(a, b, c); if (!d) { var g = a, d = e.dependentObservable; e.dependentObservable = H; a = e.isWriteableObservable(g); e.dependentObservable = d; a = H({ read: function () { L || (e.utils.arrayRemoveItem(f, g), L = !0); return g.apply(g, arguments) }, write: a && function (a) { return g(a) }, deferEvaluation: !0 }); f.push(a) } return a
            }; e.dependentObservable.fn = H.fn; e.computed = e.dependentObservable; b = e.utils.unwrapObservable(D) instanceof Array ? a[d].create({
                data: b ||
                c, parent: q, skip: M
            }) : a[d].create({ data: b || c, parent: q }); e.dependentObservable = g; e.computed = e.dependentObservable; return b
        }, t = function () { return a[d] && a[d].update instanceof Function }, v = function (b, f) { var g = { data: f || c, parent: q, target: e.utils.unwrapObservable(b) }; e.isWriteableObservable(b) && (g.observable = b); return a[d].update(g) }; if (j = I.get(c)) return j; d = d || ""; if (l) {
            var l = [], s = !1, k = function (a) { return a }; a[d] && a[d].key && (k = a[d].key, s = !0); e.isObservable(b) || (b = e.observableArray([]), b.mappedRemove = function (a) {
                var c =
                "function" == typeof a ? a : function (b) { return b === k(a) }; return b.remove(function (a) { return c(k(a)) })
            }, b.mappedRemoveAll = function (a) { var c = B(a, k); return b.remove(function (a) { return -1 != e.utils.arrayIndexOf(c, k(a)) }) }, b.mappedDestroy = function (a) { var c = "function" == typeof a ? a : function (b) { return b === k(a) }; return b.destroy(function (a) { return c(k(a)) }) }, b.mappedDestroyAll = function (a) { var c = B(a, k); return b.destroy(function (a) { return -1 != e.utils.arrayIndexOf(c, k(a)) }) }, b.mappedIndexOf = function (a) {
                var c = B(b(), k),
                a = k(a); return e.utils.arrayIndexOf(c, a)
            }, b.mappedCreate = function (a) { if (-1 !== b.mappedIndexOf(a)) throw Error("There already is an object with the key that you specified."); var c = h() ? r(a) : a; t() && (a = v(c, a), e.isWriteableObservable(c) ? c(a) : c = a); b.push(c); return c }); j = B(e.utils.unwrapObservable(b), k).sort(); g = B(c, k); s && g.sort(); var s = e.utils.compareArrays(j, g), j = {}, u, z = e.utils.unwrapObservable(c), x = {}, y = !0, g = 0; for (u = z.length; g < u; g++) { var n = k(z[g]); if (void 0 === n || n instanceof Object) { y = !1; break } x[n] = z[g] } var z =
            [], A = 0, g = 0; for (u = s.length; g < u; g++) {
                var n = s[g], m, w = i + "[" + g + "]"; switch (n.status) { case "added": var C = y ? x[n.value] : J(e.utils.unwrapObservable(c), n.value, k); m = F(void 0, C, a, d, b, w, D); h() || (m = e.utils.unwrapObservable(m)); w = N(e.utils.unwrapObservable(c), C, j); m === M ? A++ : z[w - A] = m; j[w] = !0; break; case "retained": C = y ? x[n.value] : J(e.utils.unwrapObservable(c), n.value, k); m = J(b, n.value, k); F(m, C, a, d, b, w, D); w = N(e.utils.unwrapObservable(c), C, j); z[w] = m; j[w] = !0; break; case "deleted": m = J(b, n.value, k) } l.push({
                    event: n.status,
                    item: m
                })
            } b(z); a[d] && a[d].arrayChanged && e.utils.arrayForEach(l, function (b) { a[d].arrayChanged(b.event, b.item) })
        } else if (O(c)) {
            b = e.utils.unwrapObservable(b); if (!b) { if (h()) return s = r(), t() && (s = v(s)), s; if (t()) return v(s); b = {} } t() && (b = v(b)); I.save(c, b); if (t()) return b; P(c, function (d) {
                var f = i.length ? i + "." + d : d; if (-1 == e.utils.arrayIndexOf(a.ignore, f)) if (-1 != e.utils.arrayIndexOf(a.copy, f)) b[d] = c[d]; else {
                    var g = I.get(c[d]), j = F(b[d], c[d], a, d, b, f, b), g = g || j; if (e.isWriteableObservable(b[d])) b[d](e.utils.unwrapObservable(g));
                    else b[d] = g; a.mappedProperties[f] = !0
                }
            })
        } else switch (f.getType(c)) { case "function": t() ? e.isWriteableObservable(c) ? (c(v(c)), b = c) : b = v(c) : b = c; break; default: if (e.isWriteableObservable(b)) return m = t() ? v(b) : e.utils.unwrapObservable(c), b(m), m; h() || t(); b = h() ? r() : e.observable(e.utils.unwrapObservable(c)); t() && b(v(b)) } return b
    } function N(b, c, a) { for (var d = 0, e = b.length; d < e; d++) if (!0 !== a[d] && b[d] === c) return d; return null } function Q(b, c) { var a; c && (a = c(b)); "undefined" === f.getType(a) && (a = b); return e.utils.unwrapObservable(a) }
    function J(b, c, a) { for (var b = e.utils.unwrapObservable(b), d = 0, f = b.length; d < f; d++) { var i = b[d]; if (Q(i, a) === c) return i } throw Error("When calling ko.update*, the key '" + c + "' was not found!"); } function B(b, c) { return e.utils.arrayMap(e.utils.unwrapObservable(b), function (a) { return c ? Q(a, c) : a }) } function P(b, c) { if ("array" === f.getType(b)) for (var a = 0; a < b.length; a++) c(a); else for (a in b) c(a) } function O(b) { var c = f.getType(b); return ("object" === c || "array" === c) && null !== b } function S() {
        var b = [], c = []; this.save = function (a,
        d) { var f = e.utils.arrayIndexOf(b, a); 0 <= f ? c[f] = d : (b.push(a), c.push(d)) }; this.get = function (a) { a = e.utils.arrayIndexOf(b, a); return 0 <= a ? c[a] : void 0 }
    } function R() { var b = {}, c = function (a) { var c; try { c = a } catch (e) { c = "$$$" } a = b[c]; void 0 === a && (a = new S, b[c] = a); return a }; this.save = function (a, b) { c(a).save(a, b) }; this.get = function (a) { return c(a).get(a) } } var p = "__ko_mapping__", H = e.dependentObservable, A = 0, G, I, K = ["create", "update", "key", "arrayChanged"], M = {}, u = { include: ["_destroy"], ignore: [], copy: [] }, h = u; f.isMapped = function (b) {
        return (b =
        e.utils.unwrapObservable(b)) && b[p]
    }; f.fromJS = function (b) { if (0 == arguments.length) throw Error("When calling ko.fromJS, pass the object you want to convert."); window.setTimeout(function () { A = 0 }, 0); A++ || (G = [], I = new R); var c, a; 2 == arguments.length && (arguments[1][p] ? a = arguments[1] : c = arguments[1]); 3 == arguments.length && (c = arguments[1], a = arguments[2]); a && (c = E(c, a[p])); c = y(c); var d = F(a, b, c); a && (d = a); --A || window.setTimeout(function () { for (; G.length;) { var a = G.pop(); a && a() } }, 0); d[p] = E(d[p], c); return d }; f.fromJSON =
    function (b) { var c = e.utils.parseJson(b); arguments[0] = c; return f.fromJS.apply(this, arguments) }; f.updateFromJS = function () { throw Error("ko.mapping.updateFromJS, use ko.mapping.fromJS instead. Please note that the order of parameters is different!"); }; f.updateFromJSON = function () { throw Error("ko.mapping.updateFromJSON, use ko.mapping.fromJSON instead. Please note that the order of parameters is different!"); }; f.toJS = function (b, c) {
        h || f.resetDefaultOptions(); if (0 == arguments.length) throw Error("When calling ko.mapping.toJS, pass the object you want to convert.");
        if ("array" !== f.getType(h.ignore)) throw Error("ko.mapping.defaultOptions().ignore should be an array."); if ("array" !== f.getType(h.include)) throw Error("ko.mapping.defaultOptions().include should be an array."); if ("array" !== f.getType(h.copy)) throw Error("ko.mapping.defaultOptions().copy should be an array."); c = y(c, b[p]); return f.visitModel(b, function (a) { return e.utils.unwrapObservable(a) }, c)
    }; f.toJSON = function (b, c) { var a = f.toJS(b, c); return e.utils.stringifyJson(a) }; f.defaultOptions = function () {
        if (0 < arguments.length) h =
        arguments[0]; else return h
    }; f.resetDefaultOptions = function () { h = { include: u.include.slice(0), ignore: u.ignore.slice(0), copy: u.copy.slice(0) } }; f.getType = function (b) { if (b && "object" === typeof b) { if (b.constructor == (new Date).constructor) return "date"; if ("[object Array]" === Object.prototype.toString.call(b)) return "array" } return typeof b }; f.visitModel = function (b, c, a) {
        a = a || {}; a.visitedObjects = a.visitedObjects || new R; var d, h = e.utils.unwrapObservable(b); if (O(h)) a = y(a, h[p]), c(b, a.parentName), d = "array" === f.getType(h) ?
        [] : {}; else return c(b, a.parentName); a.visitedObjects.save(b, d); var i = a.parentName; P(h, function (b) {
            if (!(a.ignore && -1 != e.utils.arrayIndexOf(a.ignore, b))) {
                var l = h[b], g = a, q = i || ""; "array" === f.getType(h) ? i && (q += "[" + b + "]") : (i && (q += "."), q += b); g.parentName = q; if (!(-1 === e.utils.arrayIndexOf(a.copy, b) && -1 === e.utils.arrayIndexOf(a.include, b) && h[p] && h[p].mappedProperties && !h[p].mappedProperties[b] && "array" !== f.getType(h))) switch (f.getType(e.utils.unwrapObservable(l))) {
                    case "object": case "array": case "undefined": g =
                    a.visitedObjects.get(l); d[b] = "undefined" !== f.getType(g) ? g : f.visitModel(l, c, a); break; default: d[b] = c(l, a.parentName)
                }
            }
        }); return d
    }
});

