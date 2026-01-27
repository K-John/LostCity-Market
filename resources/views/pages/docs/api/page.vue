<script setup lang="ts">
const auth = useAuth();
</script>

<template>
    <LayoutMain>
        <Head title="API Documentation" />

        <div class="prose prose-stone prose-invert mx-auto">
            <h1 id="markets-api-documentation">Markets API Documentation</h1>

            <p>Base path: <code>/api</code></p>

            <hr />

            <h2 id="listings">Listings</h2>

            <h3 id="get-apilistings"><code>GET /api/listings</code></h3>

            <p>
                Returns up to <strong>100 of the latest active listings</strong>

                for a given listing type. Supports an optional
                <code>since</code> filter.
            </p>

            <p>
                <strong>Controller:</strong>

                <code>App\Http\Controllers\Api\ListingController@index</code>
            </p>

            <h4 id="query-parameters">Query parameters</h4>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>

                        <th align="right">Type</th>

                        <th align="center">Required</th>

                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><code>type</code></td>

                        <td align="right">string</td>

                        <td align="center">yes</td>

                        <td>
                            Listing type (either <code>buy</code>

                            or <code>sell</code>).
                        </td>
                    </tr>

                    <tr>
                        <td><code>since</code></td>

                        <td align="right">string</td>

                        <td align="center">no</td>

                        <td>
                            Only return listings newer than the provided date.
                            Should be a parsable datetime string (e.g.
                            ISO-8601).
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 id="response">Response</h4>

            <ul>
                <li>
                    JSON array of <code>ListingData</code>

                    objects: <code>ListingData::collect($listings)</code>
                </li>
            </ul>

            <h4 id="example">Example</h4>

            <pre><code class="language-http">GET /api/listings?type=sell&amp;since=2026-01-01T00:00:00Z
Accept: application/json
</code></pre>

            <hr />

            <h3 id="get-apilistingsid"><code>GET /api/listings/{id}</code></h3>

            <p>Returns a single listing by ID.</p>

            <p>
                <strong>Controller:</strong>

                <code>App\Http\Controllers\Api\ListingController@show</code>
            </p>

            <h4 id="path-parameters">Path parameters</h4>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>

                        <th align="right">Type</th>

                        <th align="center">Required</th>

                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><code>id</code></td>

                        <td align="right">integer</td>

                        <td align="center">yes</td>

                        <td>Listing ID.</td>
                    </tr>
                </tbody>
            </table>

            <h4 id="response-1">Response</h4>

            <ul>
                <li>
                    JSON <code>ListingData</code>

                    object: <code>ListingData::from($listing)</code>
                </li>
            </ul>

            <h4 id="example-1">Example</h4>

            <pre><code class="language-http">GET /api/listings/12345
Accept: application/json
</code></pre>

            <hr />

            <h2 id="items">Items</h2>

            <h3 id="get-apiitems"><code>GET /api/items</code></h3>

            <p>
                Search endpoint for items. Returns
                <strong>up to 5</strong> items matching the query.
            </p>

            <p>
                <strong>Controller:</strong>

                <code>App\Http\Controllers\Api\ItemController@index</code>
            </p>

            <h4 id="query-parameters-1">Query parameters</h4>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>

                        <th align="right">Type</th>

                        <th align="center">Required</th>

                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><code>q</code></td>

                        <td align="right">string</td>

                        <td align="center">yes</td>

                        <td>
                            Search term. If missing/empty, returns
                            <code>[]</code>.
                        </td>
                    </tr>

                    <tr>
                        <td><code>include_unlisted</code></td>

                        <td align="right">boolean</td>

                        <td align="center">no</td>

                        <td>
                            If not truthy, restricts to
                            <code>listable()</code> items.
                        </td>
                    </tr>

                    <tr>
                        <td><code>include_banners</code></td>

                        <td align="right">boolean</td>

                        <td align="center">no</td>

                        <td>
                            If truthy, includes active banners relationship.
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 id="response-2">Response</h4>

            <ul>
                <li>
                    JSON array of <code>ItemData</code>

                    objects: <code>ItemData::collect($items)</code>
                </li>
            </ul>

            <h4 id="example-2">Example</h4>

            <pre><code class="language-http">GET /api/items?q=dragon
Accept: application/json
</code></pre>

            <hr />

            <h3 id="get-apiitemsitem"><code>GET /api/items/{item}</code></h3>

            <p>
                Returns <strong>active listings</strong> for a specific item
                (paginated).
            </p>

            <p>
                <strong>Controller:</strong>

                <code>App\Http\Controllers\Api\ItemController@show</code>
            </p>

            <h4 id="path-parameters-1">Path parameters</h4>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>

                        <th align="right">Type</th>

                        <th align="center">Required</th>

                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><code>item</code></td>

                        <td align="right">string/int</td>

                        <td align="center">yes</td>

                        <td>
                            Item route-model binding parameter (per your Laravel
                            routes).
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 id="query-parameters-2">Query parameters</h4>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>

                        <th align="right">Type</th>

                        <th align="center">Required</th>

                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><code>type</code></td>

                        <td align="right">string</td>

                        <td align="center">yes</td>

                        <td>
                            Listing type (either <code>buy</code>

                            or <code>sell</code>).
                        </td>
                    </tr>

                    <tr>
                        <td><code>page</code></td>

                        <td align="right">integer</td>

                        <td align="center">no</td>

                        <td>Standard pagination page parameter.</td>
                    </tr>
                </tbody>
            </table>

            <h4 id="response-3">Response</h4>

            <ul>
                <li>
                    Paginated JSON collection of <code>ListingData</code>

                    via <code>PaginatedDataCollection</code>

                    <br />

                    (<code
                        >ListingData::collect($listings,
                        PaginatedDataCollection::class)</code
                    >)
                </li>
            </ul>

            <h4 id="example-3">Example</h4>

            <pre><code class="language-http">GET /api/items/123?type=buy
Accept: application/json
</code></pre>
        </div>
    </LayoutMain>
</template>
