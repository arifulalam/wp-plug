//https://qr-code-styling.com/
//https://www.npmjs.com/package/qr-border-plugin

/* let qrCodeConfig = {
    containerId : "qr-code-container",
    download: true,
    type: "canvas", // "svg" or "canvas"
    shape: "circle", // "square" or "circle"
    width: 250,
    height: 250,
    data: "https://www.syncmachine.com",
    margin: 40,
    logo: "",
    dotsOptions: {
        type: "square", // "square", "rounded", "dots", "classy", "classy-rounded", "extra-rounded"
        color: "#df349e",
        roundSize: true,
        gradient: {
            type: "linear", // "linear" or "radial",
            rotation: 0,
            colorStops: [
            {
              offset: 0,
              color: "#6a1a4c",
            },
            {
              offset: 1,
              color: "green",
            },
          ],
        },
    },
    cornersSquareOptions: {
        type: "dot", // "none" or "dot" or "square" or "extra-rounded"
        color: "#000000",
        gradient: {
          type: "linear",
          rotation: 0,
          colorStops: [
            {
              offset: 0,
              color: "#000000",
            },
            {
              offset: 1,
              color: "#a61717",
            },
          ],
        },
    },
    cornersDotOptions: {
        type: "dot", // "none", "square", "dot"
        color: "#000000",
    },
    backgroundOptions: {
        color: "#ffffff",
    },
    border:{
        thickness: 10,
        color: "#000000",
        borderInnerColor: "#000000",
        borderOuterColor: "#000000",
        direction:{
            top: {
                text: "Read me on other devices",
                color: "#D5B882;",
            },
            bottom: {
                text: "SCAN ME",
                color: "#D5B882;",
            },
        }
    }
}; */

function generateQrCode() {
  if (qrCodeConfig !== null && qrCodeConfig != undefined) {
    const qrCode = new QRCodeStyling({
      type: qrCodeConfig.type, // "svg" or "canvas"
      version: 1,
      shape: qrCodeConfig.shape, // "square" or "circle"
      width: qrCodeConfig.width,
      height: qrCodeConfig.height,      
      data: qrCodeConfig.data,
      margin: qrCodeConfig.margin,
      qrOptions: {
        typeNumber: "0", // 0, 4 - 15
        mode: "Byte",
        errorCorrectionLevel: "H", // "L", "M", "Q", "H"
      },
      image: qrCodeConfig.logo,
      imageOptions: {
        saveAsBlob: true,
        hideBackgroundDots: true,
        imageSize: 0.6,
        margin: 5,
      },
      dotsOptions: {
        type: qrCodeConfig.dotsOptions.type, // "square", "rounded", "dots", "classy", "classy-rounded", "extra-rounded"
        color: qrCodeConfig.dotsOptions.color,
        roundSize: true,
        gradient: {
          type: qrCodeConfig.dotsOptions.gradient.type, // "linear" or "radial"
          rotation: qrCodeConfig.dotsOptions.gradient.rotation,
          colorStops: [
            {
              offset: qrCodeConfig.dotsOptions.gradient.colorStops[0].offset,
              color: qrCodeConfig.dotsOptions.gradient.colorStops[0].color,
            },
            {
              offset: qrCodeConfig.dotsOptions.gradient.colorStops[1].offset,
              color: qrCodeConfig.dotsOptions.gradient.colorStops[1].color,
            },
          ],
        },
      },
      cornersSquareOptions: {
        type: qrCodeConfig.cornersSquareOptions.type, // "none" or "dot" or "square" or "extra-rounded"
        color: qrCodeConfig.cornersSquareOptions.color,
        gradient: {
          type: qrCodeConfig.cornersSquareOptions.gradient.type, // "linear" or "radial"
          rotation: qrCodeConfig.cornersSquareOptions.gradient.rotation,
          colorStops: [
            {
              offset: qrCodeConfig.cornersSquareOptions.gradient.colorStops[0].offset,
              color: qrCodeConfig.cornersSquareOptions.gradient.colorStops[0].color,
            },
            {
              offset: qrCodeConfig.cornersSquareOptions.gradient.colorStops[1].offset,
              color: qrCodeConfig.cornersSquareOptions.gradient.colorStops[1].color,
            },
          ],
        },
      },
      cornersDotOptions: {
        type: qrCodeConfig.cornersDotOptions.type, // "none", "square", "dot"
        color: qrCodeConfig.cornersDotOptions.color,
      },
      backgroundOptions: {
        round: 0,
        color: qrCodeConfig.backgroundOptions.color,
        gradient: null,
      },
    });

    qrCode.applyExtension(
      QRBorderPlugin({
        round: qrCodeConfig.shape === "circle" ? 1 : 0,
        thickness: qrCodeConfig.border.thickness,
        color: qrCodeConfig.border.color,
        decorations: {
          top: {
            type: "text",
            value: qrCodeConfig.border.direction.top.text,
            style: `font: 18px sans-serif; fill: ${qrCodeConfig.border.direction.top.color};`,
          },
          bottom: {
            type: "text",
            value: qrCodeConfig.border.direction.bottom.text,
            style: `font: 18px sans-serif; fill: ${qrCodeConfig.border.direction.bottom.color};`,
          },
        },
        borderInner: {
          color: qrCodeConfig.border.borderInnerColor,
          thickness: 15,
        },
        borderOuter: {
          color: qrCodeConfig.border.borderOuterColor,
          thickness: 15,
        },
      })
    );

    // Set the license key (replace 'key' with your actual key)
    //QRBorderPlugin.setKey("key");

    qrCode.append(document.getElementById(qrCodeConfig.containerId));
    if(qrCodeConfig.download) {
      qrCode.download({
        name: "qr",
        extension: 'svg',
      });
    }
  }
}

//generateQrCode();
