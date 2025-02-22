import { getAssetFromKV } from '@cloudflare/kv-asset-handler'

addEventListener('fetch', event => {
  event.respondWith(handleEvent(event))
})

async function handleEvent(event) {
  try {
    // Try to get the asset from KV
    return await getAssetFromKV(event)
  } catch (e) {
    // If the asset is not found or there's an error, return the index page
    try {
      const response = await getAssetFromKV(event, {
        mapRequestToAsset: req => new Request(`${new URL(req.url).origin}/index.php`, req),
      })
      return response
    } catch (e) {
      return new Response('Not Found', { status: 404 })
    }
  }
}
