import pywaves as pw
import sys

try:
    privateToken    = sys.argv[1]
    recipientId     = sys.argv[2]
    assetId         = sys.argv[3]
    amount          = sys.argv[4]
except IndexError:
    print("[ERROR]")
    sys.exit()

myAddress   = pw.Address(privateKey=privateToken)
recipient   = pw.Address(recipientId)
asset       = pw.Asset(assetId)
send        = myAddress.sendAsset(recipient, asset, float(amount))

print(send)