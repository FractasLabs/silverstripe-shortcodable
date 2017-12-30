<?php

namespace SheaDawson\Shortcodable\Extensions;

use SheaDawson\Shortcodable\Shortcodable;
use SilverStripe\Core\Extension;

class ShortcodableHtmlEditorField extends Extension
{
    public function onBeforeRender()
    {
        $this->owner->setAttribute(
            'data-placeholderclasses',
            implode(',', Shortcodable::get_shortcodable_classes_with_placeholders())
        );
    }
}
