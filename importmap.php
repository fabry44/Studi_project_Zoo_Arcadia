<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => './assets/admin.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'chart.js' => [
        'version' => '3.9.1',
    ],
    'core-js/modules/es.array.concat.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.filter.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.find.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.find-index.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.includes.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.index-of.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.iterator.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.join.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.map.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.reverse.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.slice.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.sort.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.array.splice.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.date.to-json.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.number.constructor.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.object.assign.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.object.entries.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.object.keys.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.object.to-string.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.parse-float.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.parse-int.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.regexp.constructor.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.regexp.exec.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.regexp.to-string.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.includes.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.replace.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.search.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.split.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/web.dom-collections.for-each.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/web.dom-collections.iterator.js' => [
        'version' => '3.37.1',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'core-js/modules/es.object.get-prototype-of.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.ends-with.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.match.js' => [
        'version' => '3.37.1',
    ],
    'core-js/modules/es.string.starts-with.js' => [
        'version' => '3.37.1',
    ],
    'bootstrap-table/dist/bootstrap-table.min.css' => [
        'version' => '1.23.1',
        'type' => 'css',
    ],
];
