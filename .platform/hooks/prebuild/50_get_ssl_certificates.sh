#!/bin/bash

region="us-east-1"
bucket="ssl-certificates-gearbox"
folder="cloudflare"
cert="cloudflare_gearboxgo_com_cert.pem"
#chain="DigiCertCA.crt"
key="cloudflare_gearboxgo_com_key.pem"
storagePath="/etc/pki/tls/certs"
aws s3 --region ${region} cp s3://${bucket}/${folder}/${cert} ${storagePath}/certificate.pem
#aws s3 --region ${region} cp s3://${bucket}/${folder}/${chain} ${storagePath}/ssl-bundle.crt
aws s3 --region ${region} cp s3://${bucket}/${folder}/${key} ${storagePath}/key.pem

# Chain certificate and CA chain

#cat ${storagePath}/certificate.pem ${storagePath}/ssl-bundle.crt > ${storagePath}/certificate-chained.pem
