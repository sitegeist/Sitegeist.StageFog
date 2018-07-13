# Sitegeist.StageFog
## Control reloading of Nodes Documents when children are changed

This package helps to tackle cases where nodes have to be rendered with
the containing collection or the whole document.

Possible reasons for doing this are js-interactions (Maps/Sliders etc.)
or complex fusion-rendering where a child node is rendered in multiple
places of a document.

*Please use this with care, this is for exceptional conditions only*

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored by our employer https://www.sitegeist.de.*

## Configuration

If a node or one of the parent-nodes (until the document) has the following
configuration option the partial reloading for this nodes is skipped
and the whole document is reloaded instead.

```
Vendor.Site:Content.ExampleCollection:
  options:
    reloadPageIfChanged: TRUE
```

## Installation

Sitegeist.StageFog is available via packagist. Run `composer require sitegeist/stagefog`.
We use semantic-versioning so every breaking change will increase the major-version number.


## Contribution

We will gladly accept contributions. Please send us pull requests.
