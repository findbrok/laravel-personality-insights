<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Illuminate\Support\Collection;
use FindBrok\PersonalityInsights\Exceptions\MissingParameterContentItemException;

class ContentItem extends Collection
{
    /**
     * The Content of the ContentItem.
     *
     * @var array
     */
    protected $items = [

        /*
        |--------------------------------------------------------------------------
        | id [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | A unique identifier for this content item
        |
        */

        'id' => '',

        /*
        |--------------------------------------------------------------------------
        | content [string] | (Required)
        |--------------------------------------------------------------------------
        |
        | A maximum of 20 MB of content to be analyzed
        |
        */

        'content' => '',

        /*
        |--------------------------------------------------------------------------
        | userid [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | A unique identifier for the author of this content
        |
        */

        'userid' => '',

        /*
        |--------------------------------------------------------------------------
        | sourceid [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | An identifier for the source of this content; for example, blog123 or twitter
        |
        */

        'sourceid' => '',

        /*
        |--------------------------------------------------------------------------
        | created [integer] | (Optional)
        |--------------------------------------------------------------------------
        |
        | When was the content created, UNIX Timestamp
        |
        */

        'created' => '',

        /*
        |--------------------------------------------------------------------------
        | updated [integer] | (Optional)
        |--------------------------------------------------------------------------
        |
        | When was the content updated, UNIX Timestamp
        |
        */

        'updated' => '',

        /*
        |--------------------------------------------------------------------------
        | contenttype [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | The MIME type of the content: Defaults to text/plain
        | - text/plain
        | - text/html
        |
        */

        'contenttype' => '',

        /*
        |--------------------------------------------------------------------------
        | language [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | The language of the content as a two-letter ISO 639-1
        | identifier: Defaults to en
        | - ar (Arabic)
        | - en (English, the default)
        | - es (Spanish)
        | - ja (Japanese)
        | Regional variants are treated as their parent
        | language; for example, en-US is interpreted
        | as en.
        |
        */

        'language' => '',

        /*
        |--------------------------------------------------------------------------
        | parentid [string] | (Optional)
        |--------------------------------------------------------------------------
        |
        | The unique ID of the parent content item for this item. Used to
        | identify hierarchical relationships between posts/replies,
        | messages/replies, and so on.
        |
        */

        'parentid' => '',

        /*
        |--------------------------------------------------------------------------
        | reply [boolean] | (Optional)
        |--------------------------------------------------------------------------
        |
        | Indicates whether this content item is a reply to another content item.
        |
        */

        'reply' => '',

        /*
        |--------------------------------------------------------------------------
        | forward [boolean] | (Optional)
        |--------------------------------------------------------------------------
        |
        | Indicates whether this content item is a forwarded/copied version of
        | another content item.
        |
        */

        'forward' => '',
    ];

    /**
     * Create a new ContentItem.
     *
     *
     * @param array $items
     *
     * @throws MissingParameterContentItemException
     */
    public function __construct($items = [])
    {
        //New Up parent
        parent::__construct($items);

        //If we do not have content then throw an Exception
        if (! $this->has('content')) {
            throw new MissingParameterContentItemException('Personality Insights requires a content', 422);
        }
    }
}
