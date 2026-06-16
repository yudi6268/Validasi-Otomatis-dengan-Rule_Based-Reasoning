<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Perjanjian Kinerja - Preview</title>
    @if(empty($for_pdf) || !$for_pdf)
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        < !-- FOR PDF: Set page margins and size -->

        @if(!empty($for_pdf) && $for_pdf)
            @page {
                size: 215.9mm 330.2mm; /* F4 / Folio */
                margin: 0;
            }

            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                background: #fff;
                font-family: Arial, sans-serif;
            }

            .page {
                width: 100%;
                /* Reduce padding to fit more content */
                padding: 10mm 15mm;
                position: relative;
                page-break-after: always;
                /* overflow: hidden;  */
                box-sizing: border-box;
                font-size: 10pt !important;
                line-height: 1.15;
            }

            .page:last-child {
                page-break-after: auto;
            }

            /* Adjust header for PDF */
            .header {
                margin-bottom: 5px !important;
                padding-bottom: 0 !important;
            }
            .header div {
                font-size: 11pt !important;
                margin-bottom: 2px !important;
            }
            .header .logo {
                max-height: 40px !important;
                margin-bottom: 2px !important;
            }

            /* Adjust content section for PDF */
            .content-section {
                font-size: 10pt !important;
                line-height: 1.25 !important;
                /* Remove fixed width if set for browser */
                width: 100%; 
                margin-top: 5px !important;
            }
            
            /* Tighten paragraph spacing */
            .content-section div {
                margin-bottom: 3px !important;
            }

            /* Force landscape page break */
            .page-landscape {
                page: landscape !important;
                /* page-break-before: always; */
                width: 100%;
                padding: 10mm 12mm;
                position: relative;
                /* page-break-before: always; */
                /* page-break-after: always; */
                /* overflow: hidden; */
                box-sizing: border-box;
                font-size: 9pt !important; /* Smaller font for dense tables */
            }

            /* Hide UI elements in PDF */
            .user-header-fixed,
            .status-badge,
            .footer-fixed,
            .bell-icon,
            .notification-dot,
            .aksi-container,
            .modal-overlay,
            #modal-setujui,
            #modal-tolak,
            #modal-alasan-tolak {
                display: none !important;
            }

            .preview-center-wrapper {
                background: #fff !important;
                padding: 0 !important;
                display: block !important;
                min-height: 0 !important;
                width: 100% !important;
            }

            .preview-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }

            /* Signature layout */
            .sig-flex-row {
                display: table !important;
                width: 100% !important;
                table-layout: fixed;
                margin-top: 5px !important; /* Minimal margin */
            }

            .sig-flex-col {
                display: table-cell !important;
                width: 50% !important;
                vertical-align: top !important;
                padding: 0 5px;
            }
            
            /* Signature items tight spacing */
            .sig-flex-col br {
                line-height: 1.0 !important;
                display: block;
                margin: 2px 0;
            }

            .sig-flex-col img {
                max-height: 45px !important; /* Smaller signature image */
                margin: 2px 0 !important;
            }
            
            /* Tighten table spacing for PDF */
            table th, table td {
                padding: 2px 3px !important;
                font-size: 9pt !important;
                word-wrap: break-word;
                line-height: 1.1;
            }

            .page-landscape table th, .page-landscape table td {
                 font-size: 8.5pt !important; /* Even smaller for landscape table */
                 padding: 2px 2px !important;
            }
        @endif
        html, body {
            height: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            width: 100%;
            background: #e6fcfc;
        }

        body {
            background: #fff;
        }

        .preview-center-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #e6fcfc;
            padding: 0;
            padding-bottom: 80px;
        }

        .preview-card {
            background: transparent !important;
            border-radius: 0;
            box-shadow: none;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
            display: block;
            width: 100%;
        }

        .header {
            background: #fff !important;
            border-radius: 16px 16px 0 0;
            padding-top: 16px;
            padding-bottom: 8px;
            text-align: center;
            margin-bottom: 24px;
            width: 100%;
        }

        .user-header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70px;
        }

        .user-header-fixed h1 {
            margin: 0;
            text-align: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .bell-icon {
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            display: flex !important;
            align-items: center;
            justify-content: center;
            z-index: 1001;
        }

        .notification-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #ff2222;
            border-radius: 50%;
            border: 2px solid #fff;
            display: block !important;
        }

        .status-badge {
            position: fixed;
            top: 75px;
            right: 24px;
            padding: 10px 16px;
            border-radius: 8px;
            z-index: 9999;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            height: auto;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .footer-fixed {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background: #fff;
            color: #222;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            padding: 14px 0;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
            z-index: 100;
        }

        @media print {
            .footer-fixed {
                display: none !important;
            }

            .user-header-fixed {
                display: none !important;
            }

            .status-badge {
                display: none !important;
            }

            .bell-icon {
                display: none !important;
            }

            .modal-overlay {
                display: none !important;
            }

            .preview-center-wrapper {
                background: #fff !important;
                padding: 0 !important;
                display: block !important;
                min-height: 0 !important;
            }

            .preview-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: none !important;
                display: block !important;
            }

            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        table th {
            background: #e0e0e0;
            color: #222;
            font-weight: 600;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 10px;
        }

        /* FOR BROWSER PREVIEW ONLY - shows page in card format (F4 approximation) */
        @if(empty($for_pdf) || !$for_pdf)
            .page {
                width: 216mm;
                height: 330mm;
                margin: 25mm auto;
                background: white;
                padding: 15mm 12mm;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
                border: none;
                border-radius: 0;
                page-break-after: always;
                page-break-inside: avoid;
                position: relative;
                display: block;
            }

        @endif .page::after {
            content: '';
            position: absolute;
            bottom: -25mm;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 2px;
            background: linear-gradient(to right, transparent, #009970, transparent);
        }

        .page-landscape {
            page: landscape !important;
            /* page-break-before: always; */
            width: 330mm !important;
            /* F4 Landscape Width */
            height: 216mm !important;
            /* F4 Landscape Height */
            /* page-break-after: always; */
            /* page-break-inside: avoid; */
            padding: 12mm 15mm !important;
        }

        .page-landscape .content-section {
            overflow-x: auto;
        }

        .page-landscape table {
            font-size: 10px !important;
        }

        .page-landscape table th,
        .page-landscape table td {
            font-size: 10px !important;
            padding: 4px 3px !important;
            line-height: 1.3 !important;
        }

        .page-landscape .header div {
            font-size: 10px !important;
            margin-bottom: 1px !important;
        }

        .page-break {
            page-break-before: auto;
        }

        body {
            background: #e0e0e0;
            color: #222;
        }

        .rejected-stamp {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #DC3545;
            color: white;
            padding: 15px 25px;
            border: 4px solid #DC3545;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transform: rotate(0deg);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            z-index: 100;
        }

        .rejection-notice {
            background: #f8d7da;
            border: 2px solid #DC3545;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            position: relative;
        }

        .rejection-notice-title {
            color: #721c24;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rejection-notice-title::before {
            content: "⚠";
            font-size: 20px;
        }

        .rejection-notice-content {
            color: #721c24;
            font-size: 12px;
            line-height: 1.6;
            background: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .rejection-notice-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 0px;
            /* border-bottom removed */
            padding-bottom: 0px;
        }

        .page {
            width: 216mm;
            /* F4 Width */
            margin: 0 auto !important;
            background: white;
            padding: 0 12mm !important;
            box-shadow: none !important;
            page-break-after: always;
            position: relative;
            line-height: 1.5;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .content-section {
            margin-top: 15px;
            font-family: Arial, sans-serif !important;
            font-size: 12px
            line-height: 1.5;
        }

        .section-title {
            display: none !important;
        }

        /* FOR BROWSER: Flex layout for nice spacing */
        .parties {
            display: flex;
            gap: 40px;
            margin-bottom: 25px;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .party {
            flex: 1;
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        /* FOR PDF: Override with table-based layout for DomPDF */
        @if(!empty($for_pdf) && $for_pdf)
            .parties {
                display: table;
                width: 100%;
                border-collapse: collapse;
                gap: 0;
                margin-bottom: 25px;
            }

            .party {
                display: table-cell;
                width: 50%;
                padding: 0 15px;
                vertical-align: top;
                text-align: center;
            }

        @endif .party strong {
            display: block;
            margin-bottom: 3px;
        }

        .party-name {
            font-weight: 600;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 10px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        @media print {
            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tr {
                page-break-inside: avoid;
            }

            /* Repeat header jika signature pindah ke halaman baru */
            .header-repeat {
                /* display: block !important;
                page-break-before: always; */
                display: none;
                margin-bottom: 20px;
            }
        }

        /* Fixed table variants for better print fitting */
        .table {
            width: 100%;
            max-width: 100%;
        }

        .table-fixed {
            table-layout: fixed;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            width: 100%;
        }

        .table-b th,
        .table-b td,
        .table-c th,
        .table-c td {
            word-break: break-word;
            overflow-wrap: anywhere;
            white-space: normal;
        }

        /* Ensure tables don't overflow on landscape pages */
        .page-landscape table {
            max-width: 100%;
            table-layout: fixed;
        }

        .page-landscape table th,
        .page-landscape table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 0;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table th {
            background: #e7e7e7;
            color: #222;
            font-weight: 600;
            text-align: center;
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 5px 3px;
        }

        table td {
            vertical-align: top;
            line-height: 1.4;
            margin: 0;
            padding: 5px 3px;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            color: #999;
            font-style: italic;
            font-size: 12px;
        }

        .total-row {
            background: #009970;
            color: white;
            font-weight: bold;
        }

        .total-row td {
            padding: 0;
            font-weight: bold;
            line-height: 1.5;
            margin: 0;
        }

        /* Table wrapper for mobile scrolling */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media screen and (max-width: 768px) {
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-bottom: 10px;
            }

            .table-responsive table {
                min-width: 600px;
            }
        }

        p {
            text-align: justify;
            margin: 10px 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        h3 {
            text-align: center;
            margin: 15px 0 10px 0;
            font-size: 12px;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 12px;
        }

        .signature-block {
            text-align: center;
            width: 45%;
            min-height: 160px;
            page-break-inside: avoid;
        }

        .signature-wrapper {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .preview-wrapper {
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        @media print {
            .signature-wrapper {
        display:block !important;
        page-break-before:auto !important;
        page-break-after:auto !important;
        page-break-inside:auto !important;
        margin-top:30px;
    }

            .signature-block {
                width: 100%;
                text-align: center;
            }

            .signature-block img {
                max-height: 70px;
                height: auto;
            }
        }

        .signature-block .sig-line {
            height: 80px;
            margin: 20px 0;
            border-bottom: 1px solid #000;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .signature-block .sig-line img {
            max-height: 70px;
            max-width: 100%;
        }

        .signature-block .name {
            font-weight: 600;
            margin-top: 5px;
        }

        .page-break {
            page-break-after: always;
            margin-top: 40px;
        }

        /* MOBILE RESPONSIVE STYLES */
        @media screen and (max-width: 768px) {
            body {
                padding: 5px;
                font-size: 10px;
            }

            .page {
                width: 100%;
                height: auto;
                min-height: auto;
                margin: 5px auto;
                padding: 15px 10px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            }

            .rejected-stamp {
                top: 10px;
                right: 10px;
                padding: 10px 15px;
                font-size: 10px;
                border-width: 3px;
            }

            .rejection-notice {
                padding: 15px;
                margin: 15px 0;
            }

            .rejection-notice-title {
                font-size: 11px;
            }

            .rejection-notice-content {
                font-size: 10px;
                padding: 10px;
            }

            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }

            .logo {
                height: 45px;
            }

            .header h1 {
                font-size: 10px;
            }

            .header p {
                font-size: 9px;
            }

            .section-title {
                font-size: 10px;
                padding: 6px 8px;
                margin: 8px 0;
            }

            table {
                font-size: 9px;
            }

            table th,
            table td {
                padding: 4px 3px;
                font-size: 9px;
            }

            .parties {
                flex-direction: column;
                gap: 15px;
            }

            .party {
                font-size: 10px;
            }
        }

        @media screen and (max-width: 480px) {
            body {
                font-size: 9px;
                padding: 2px;
            }

            .page {
                padding: 10px 8px;
                margin: 3px auto;
            }

            .logo {
                height: 35px;
            }

            .header h1 {
                font-size: 9px;
            }

            .header p {
                font-size: 8px;
            }

            .section-title {
                font-size: 9px;
                padding: 5px 6px;
            }

            table {
                font-size: 8px;
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            table th,
            table td {
                padding: 3px 2px;
                font-size: 8px;
            }

            .content-section p {
                font-size: 8px;
                line-height: 1.4;
            }
        }

        @if(empty($for_pdf) || !$for_pdf)
            @media print {
                * {
                    margin: 0;
                    padding: 0;
                }

                body {
                    background: white;
                    padding: 0;
                    margin: 0;
                }

                .page {
                    width: 100%;
                    max-width: 216mm;
                    min-height: 330mm;
                    margin: 0;
                    padding: 15mm 12mm !important;
                    box-shadow: none;
                    page-break-after: always;
                    page-break-inside: avoid;
                    box-sizing: border-box;
                }

                /* Ensure signature elements display correctly in PDF */
                .sig-nama {
                    font-weight: 600;
                    font-size: 11px;
                    margin-bottom: 2px;
                    word-wrap: break-word;
                    display: inline-block;
                    max-width: 100%;
                }

                .sig-garis {
                    border-bottom: 1px solid #000;
                    margin: 2px 0 3px 0;
                    display: block;
                    min-width: 80px;
                    height: 0;
                }

                .section-title {
                    display: none !important;
                }

                table th,
                table td {
                    padding: 2px 3px !important;
                    line-height: 1.3 !important;
                    margin: 0 !important;
                    font-size: 9pt !important;
                }
            }

            @page {
                /* Prefer named F4 and portrait to match Dompdf's setPaper('F4','portrait') */
                size: 210mm 330mm;
                margin: 0;
            }

            @page landscape {
                size: 297mm 210mm;
                margin: 10mm;
            }

            @if(empty($for_pdf) || !$for_pdf)
            @media print {
                .page {
                    width: 100%;
                    max-width: 210mm;
                    min-height: 330mm;
                    margin: 0;
                    padding: 15mm 12mm !important;
                    box-shadow: none;
                    page-break-after: always;
                    page-break-inside: avoid;
                    box-sizing: border-box;
                }

                .page-landscape {
                    page: landscape !important;
                    /* page-break-before: always; */
                    width: 297mm !important;
                    height: 210mm !important;
                    padding: 8mm !important;
                    box-sizing: border-box;
                }

                .page-landscape table {
                    width: 100% !important;
                    max-width: 100% !important;
                }

                table,
                table th,
                table td {
                    font-size: 9pt !important;
                    line-height: 1.2 !important;
                    padding: 2px 3px !important;
                }

                table th {
                    padding: 3px 2px !important;
                }
            }
            @endif

        @endif

        /* Reduce header sizes slightly for PDF */
        .header h1 {
            font-size: 11px !important;
        }

        .logo {
            height: 48px !important;
            max-height: 60px !important;
            width: auto !important;
            display: inline-block;
        }

        /* Ensure signatures keep correct height */
        .signature-block .sig-line {
            height: 80px !important;
        }

        /* Signature styling for proper alignment and garis width */
        .sig-nama {
            font-weight: 600;
            font-size: 12px;
            margin: 0 auto 1px auto;
            word-wrap: break-word;
            text-align: center;
            max-width: 100%;
            display: block;
            line-height: 1.0;
        }

        .sig-garis {
            border-bottom: 1px solid #000;
            margin: 0 auto 1px auto;
            display: block;
            width: auto;
            min-width: 60px;
            max-width: 220px;
            height: 0;
        }
    </style>

    {{-- Redundant PDF CSS block removed --}}

    @if($isDirektur)
        <style>
            body,
            html {
                font-family: Arial,
                    sans-serif !important;
                font-size: 11pt !important;
            }

            .page {
                font-family: Arial, sans-serif !important;
                font-size: 11pt !important;
                padding-top: 24px !important;
                margin-top: 2.5cm !important;
                margin-bottom: 2.5cm !important;
                margin-left: 2.5cm !important;
                margin-right: 2cm !important;
            }

            .header {
                margin-top: 0 !important;
                margin-bottom: 18px !important;
                text-align: center !important;
            }

            .header h1,
            .header p {
                font-family: Arial, sans-serif !important;
                font-size: 14pt !important;
                font-weight: 700 !important;
                margin: 0 0 2px 0 !important;
                text-align: center !important;
                letter-spacing: 0.5px;
                line-height: 1.15 !important;
            }

            .header h1:last-of-type,
            .header p {
                margin-bottom: 6px !important;
            }

            .judul-halaman {
                font-family: Arial, sans-serif !important;
                font-size: 12pt !important;
                font-weight: 700 !important;
                text-align: center !important;
                margin: 18px 0 12px 0 !important;
            }

            .content-section,
            .parties,
            .party,
            .table,
            .table th,
            .table td,
            p,
            td,
            th,
            .identitas,
            .ttd-block,
            .ttd-block * {
                font-family: Arial, sans-serif !important;
                font-size: 11pt !important;
                line-height: 1.15 !important;
            }

            table th,
            table td {
                padding: 3px 2px !important;
                font-size: 10pt !important;
            }
        </style>
    @else

        <style>
            html,
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 0;
                min-height: 100vh;
            }

            .preview-center-wrapper {
                width: 100vw;
                min-height: 100vh;
                display: block;
                background: #e6fcfc;
            }

            .preview-card {
                background: #fff !important;
                border-radius: 16px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                max-width: 900px;
                margin: 40px auto 32px auto;
                padding: 40px 50px 32px 50px;
                display: block;
                width: 100%;
            }

            .header {
                background: #fff !important;
                border-radius: 16px 16px 0 0;
                padding-top: 16px;
                padding-bottom: 8px;
                text-align: center;
                margin-bottom: 24px;
                width: 100%;
            }

            .footer-fixed {
                position: static;
                left: 0;
                bottom: 0;
                width: 100vw;
                background: #fff;
                color: #222;
                text-align: center;
                font-size: 1.08rem;
                font-weight: 600;
                padding: 14px 0 12px 0;
                box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
                letter-spacing: 0.1px;
                z-index: 100;
                border-radius: 0 0 16px 16px;
                margin: 0 auto;
            }
        </style>
    @endif

    <style>
        @if(empty($for_pdf) || !$for_pdf)
        @media print {

            body,
            html {
                font-family: Arial, sans-serif !important;
                font-size: 11pt !important;
                line-height: 1.15 !important;
            }

            p,
            td,
            th,
            li,
            span,
            div {
                font-size: 11pt !important;
                line-height: 1.15 !important;
            }

            table {
                font-size: 11pt !important;
                line-height: 1.15 !important;
            }

            h1 {
                font-size: 14pt !important;
            }

            h2 {
                font-size: 13pt !important;
            }

            h3 {
                font-size: 11pt !important;
                font-weight: 700 !important;
            }
        }
        @endif
    </style>
</head>

<body style="background:#e6fcfc;min-height:100vh;">

    @php
        // Handle tabelA, tabelB, tabelC - check if already array or JSON string
        $tabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
        // Penentuan role dan status preview
        $user = auth()->user();
        // Cek role direktur/pimpinan hanya berdasarkan jabatan (bukan id)
        $isDirektur = false;
        if ($user) {
            $jabatan = strtolower($user->jabatan ?? '');
            // Hanya jabatan yang persis "direktur" atau mengandung "direktur" TANPA diawali "wakil "
            if ((\Illuminate\Support\Str::contains($jabatan, 'direktur') && !\Illuminate\Support\Str::startsWith($jabatan, 'wakil ')) || \Illuminate\Support\Str::contains($jabatan, 'pimpinan')) {
                $isDirektur = true;
            }
        }
        // Default
        $showAksi = false;
        $showAlasanTolak = false;
        $showTtdPihak2 = false;
        $showBarDitolak = false;
        // Logic preview dinamis
        // Kunci logika preview direktur dan user
        // Semua blok aksi/modal/notifikasi bar hanya untuk direktur

        // Override status dari query (untuk refresh cepat setelah aksi)
        $statusQuery = request()->get('status');
        if (in_array($statusQuery, ['ditolak', 'disetujui', 'menunggu'], true)) {
            $status = $statusQuery;
        }

        $backUrl = route('perjanjian.index');
        if (request()->get('from') === 'dashboard_wadir_perjanjian') {
            $backUrl = route('dashboard.wadir', ['panel' => 'perjanjian']);
        }
        // Default: no blocking variable (may be passed from controller)
        if (!isset($approvedOther)) { $approvedOther = null; }
    @endphp



    {{-- Header with title for user --}}
    @if(!$isDirektur)
        <div class="user-header-fixed">
            <h1 style="font-size:20px;font-weight:700;color:#009970;margin:0;">PERJANJIAN</h1>

            @if(isset($perjanjian->rejected) && $perjanjian->rejected)
                <div class="bell-icon" onclick='document.getElementById("modal-alasan-tolak").style.display="flex";'>
                    <span style="color:#009970;font-size:24px;position:relative;">🔔</span>
                    <span class="notification-dot"></span>
                </div>
            @endif
        </div>
        <div style="height:70px;"></div>
    @endif

    {{-- Header with title for direktur --}}
    @if($isDirektur)
        <div class="user-header-fixed">
            <h1 style="font-size:20px;font-weight:700;color:#009970;margin:0;">PERJANJIAN</h1>
        </div>
        <div style="height:70px;"></div>
    @endif

    {{-- Tombol Setujui/Tolak hanya untuk Direktur/Pimpinan (bukan user/pihak pertama) --}}
    {{-- Blok aksi/modal hanya untuk direktur/pimpinan --}}
    @if($isDirektur && $status === 'menunggu')
        <style>
            .aksi-container {
                position: fixed;
                top: 88px;
                right: 20px;
                z-index: 1200;
                display: flex;
                gap: 12px;
                align-items: center;
            }

            .aksi-btn {
                padding: 14px 28px;
                border: none;
                border-radius: 8px;
                font-weight: 700;
                font-size: 15px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: transform 0.18s cubic-bezier(.4, 2, .6, 1), box-shadow 0.18s, background 0.18s;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                backdrop-filter: blur(2px);
            }

            .aksi-btn:hover {
                transform: scale(1.06);
                box-shadow: 0 6px 24px rgba(0, 0, 0, 0.13);
            }

            .aksi-btn:active {
                transform: scale(0.96);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            }

            .aksi-btn.terima {
                background: #F5E94E;
                color: #222;
            }

            .aksi-btn.tolak {
                background: #FF2E2E;
                color: #fff;
            }
        </style>
        <div class="aksi-container">
            <button id="btn-setujui" type="button" class="aksi-btn terima"
                onclick="@if(!empty($approvedOther))document.getElementById('modal-sudah-disetujui').style.display='flex'@else document.getElementById('modal-setujui').style.display='flex'@endif">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M13.485 1.929a1 1 0 0 1 0 1.414l-7.071 7.071a1 1 0 0 1-1.414 0L2.515 8.071a1 1 0 1 1 1.414-1.414l1.071 1.071 6.364-6.364a1 1 0 0 1 1.414 0z" />
                </svg>
                Terima
            </button>
            <button id="btn-tolak" type="button" class="aksi-btn tolak"
                onclick="document.getElementById('modal-tolak').style.display='flex'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                </svg>
                Tolak
            </button>
        </div>
        <!-- Modal Notifikasi Persetujuan -->
        <div id="modal-setujui"
            style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25); z-index:9999; align-items:center; justify-content:center;">
            <div
                style="background:#fff; border-radius:12px; padding:32px 28px; max-width:420px; width:95vw; box-shadow:0 4px 24px rgba(0,0,0,0.18); position:relative;">
                <h2 style="text-align:center; font-size:22px; font-weight:700; margin-bottom:18px;">Konfirmasi Persetujuan
                </h2>
                <p style="text-align:center; font-size:15px; margin-bottom:22px;">Apa anda yakin untuk menyetujui perjanjian
                    ini?</p>
                <form id="form-setujui" action="{{ route('direktur.perjanjian.approve', $perjanjian->id) }}" method="POST">
                    @csrf
                    <div style="display:flex;justify-content:center;gap:12px;">
                        <button type="button" onclick="document.getElementById('modal-setujui').style.display='none'"
                            style="background:#6c757d;color:#fff;padding:10px 32px;border:none;border-radius:7px;font-weight:bold;font-size:17px; cursor:pointer;">Batal</button>
                        <button id="btn-submit-setujui" type="submit"
                            style="background:#F5E94E;color:#222;padding:10px 32px;border:none;border-radius:7px;font-weight:bold;font-size:17px; cursor:pointer;">Setujui</button>
                    </div>
                </form>
                <button onclick="document.getElementById('modal-setujui').style.display='none'"
                    style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;font-weight:bold;color:#888;cursor:pointer;">&times;</button>
            </div>
        </div>
        <!-- Modal Alasan Tolak -->
        <div id="modal-tolak"
            style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25); z-index:9999; align-items:center; justify-content:center;">
            <div
                style="background:#fff; border-radius:12px; padding:32px 28px; max-width:420px; width:95vw; box-shadow:0 4px 24px rgba(0,0,0,0.18); position:relative;">
                <h2 style="text-align:center; font-size:22px; font-weight:700; margin-bottom:18px;">ALASAN MENOLAK</h2>
                <form id="form-tolak" action="{{ route('direktur.perjanjian.reject', $perjanjian->id) }}" method="POST"
                    autocomplete="off">
                    @csrf
                    <input type="text" name="nama" value="{{ $perjanjian->pihak2_name ?? '' }}" readonly
                        placeholder="Nama Lengkap"
                        style="width:100%;margin-bottom:10px;padding:8px 10px;border:1px solid #bbb;border-radius:6px;font-size:15px;">
                    <input type="text" name="jabatan" value="{{ $perjanjian->pihak2_jabatan ?? '' }}" readonly
                        placeholder="Jabatan"
                        style="width:100%;margin-bottom:10px;padding:8px 10px;border:1px solid #bbb;border-radius:6px;font-size:15px;">
                    <input type="text" name="tanggal"
                        value="{{ \Carbon\Carbon::parse($perjanjian->agreement_date ?? $perjanjian->created_at)->translatedFormat('d-m-Y') }}"
                        readonly placeholder="Tanggal"
                        style="width:100%;margin-bottom:10px;padding:8px 10px;border:1px solid #bbb;border-radius:6px;font-size:15px;">
                    <textarea name="rejection_reason" required placeholder="Tulis Alasan"
                        style="width:100%;min-height:100px;margin-bottom:16px;padding:8px 10px;border:1px solid #bbb;border-radius:6px;font-size:15px;"></textarea>
                    <div style="display:flex;justify-content:center;">
                        <button id="btn-submit-tolak" type="submit"
                            style="background:#0DA45C;color:#fff;padding:10px 32px;border:none;border-radius:7px;font-weight:bold;font-size:17px; cursor:pointer;">KIRIM
                            ALASAN</button>
                    </div>
                </form>
                <button onclick="document.getElementById('modal-tolak').style.display='none'"
                    style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;font-weight:bold;color:#888;cursor:pointer;">&times;</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', func tion() {
                var btnSetujui = document.getElementById('btn-setujui');
                var modalSetujui = document.getElementById('modal-setujui');
                var formSetujui = document.getElementById('form-setujui');
                var btnSubmitSetujui = document.getElementById('btn-submit-setujui');
                var btnSubmitTolak = document.getElementById('btn-submit-tolak');
                var btnTolak = document.getElementById('btn-tolak');
                var modalTolak = document.getElementById('modal-tolak');
                var formTolak = document.getElementById('form-tolak');

                // Show modal setujui
                if(btnSetujui && modalSetujui) {
                btnSetujui.onclick = function () {
                    modalSetujui.style.display = 'flex';
                };
            }
            // Handle submit setujui
            if (formSetujui) {
                formSetujui.onsubmit = function (e) {
                    e.preventDefault();
                    if (btnSubmitSetujui) {
                        btnSubmitSetujui.disabled = true;
                        btnSubmitSetujui.innerHTML = 'Memproses...';
                    }
                    var formData = new FormData(formSetujui);
                    fetch(formSetujui.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formSetujui.querySelector('[name=_token]').value
                        },
                        bod                                                                    y: formData,
                        cache: 'no-store',
                        credentials: 'same-origin'
                    })
                        .then(data => {
                            try {
                                if (window.opener && window.opener.postMessage) {
                                    window.opener.postMessage({
                                        type: 'PERJANJIAN_STATUS_CHANGED',
                                        id: perjanjianId
                                    }, window.location.origin);
                                }
                            } catch (e) { }

                            const target = window.location.pathname + '?status=disetujui&ts=' + Date.now();
                            window.location = target;
                        })
                        .catch(() => {
                            try {
                                if (window.opener && window.opener.postMessage) {
                                    window.opener.postMessage({
                                        type: 'PERJANJIAN_STATUS_CHANGED',
                                        id: perjanjianId
                                    }, window.location.origin);
                                }
                            } catch (e) { }

                            const target = window.location.pathname + '?status=disetujui&ts=' + Date.now();
                            window.location = target;
                        });
                };
            }
            // Show modal alasan tolak
            if (btnTolak && modalTolak) {
                btnTolak.onclick = function () {
                    modalTolak.style.display = 'flex';
                };
            }
            // Handle submit alasan dengan AJAX
            if (formTolak) {
                formTolak.onsubmit = function (e) {
                    e.preventDefault();
                    if (btnSubmitTolak) {
                        btnSubmitTolak.disabled = true;
                        btnSubmitTolak.innerHTML = 'Mengirim...';
                    }
                    var formData = new FormData(formTolak);
                    fetch(formTolak.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formTolak.querySelector('[name=_token]').value
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            try { if (window.opener && window.opener.postMessage) { window.opener.postMessage({ type: 'PERJANJIAN_STATUS_CHANGED', id: {{ (int) $perjanjian->id }} }, window.location.origin); } } catch (e) { }
                            // Sukses tolak -> reload preview (cache-busted) agar badge "Perjanjian Ditolak" tampil
                            const target = window.location.pathname + '?status=ditolak&ts=' + Date.now();
                            window.location = target;
                        })
                        .catch(() => {
                            try { if (window.opener && window.opener.postMessage) { window.opener.postMessage({ type: 'PERJANJIAN_STATUS_CHANGED', id: {{ (int) $perjanjian->id }} }, window.location.origin); } } catch (e) { }
                            const target = window.location.pathname + '?status=ditolak&ts=' + Date.now();
                            window.location = target;
                        });
                };
            }
                                                });
        </script>

        @if(!empty($approvedOther))
        {{-- Modal: sudah ada perjanjian disetujui --}}
        <div id="modal-sudah-disetujui"
            style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); z-index:9999; align-items:center; justify-content:center;">
            <div style="background:#fff; border-radius:14px; padding:36px 28px; max-width:440px; width:95vw; box-shadow:0 6px 32px rgba(0,0,0,0.18); position:relative; text-align:center;">
                <div style="margin-bottom:14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" fill="#FFA500" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                </div>
                <h2 style="font-size:19px; font-weight:700; margin-bottom:12px; color:#1B2A41;">Persetujuan Tidak Dapat Dilakukan</h2>
                <p style="font-size:14px; color:#555; line-height:1.6; margin-bottom:22px;">
                    Sudah terdapat Perjanjian Kinerja atas nama <strong>{{ $perjanjian->pihak1_name }}</strong>
                    yang telah disetujui sebelumnya
                    (disetujui pada <strong>{{ optional($approvedOther->updated_at)->translatedFormat('d F Y') }}</strong>).
                    <br><br>
                    Perjanjian baru tidak dapat disetujui selama masih ada perjanjian yang aktif.
                </p>
                <button onclick="document.getElementById('modal-sudah-disetujui').style.display='none'"
                    style="background:#009970;color:#fff;padding:10px 36px;border:none;border-radius:8px;font-weight:700;font-size:15px;cursor:pointer;">
                    Mengerti
                </button>
                <button onclick="document.getElementById('modal-sudah-disetujui').style.display='none'"
                    style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;font-weight:bold;color:#aaa;cursor:pointer;">&times;</button>
            </div>
        </div>
        @endif
    @endif

    {{-- Wrapper for centered preview card (only for non-print view) --}}
    <div class="preview-center-wrapper">
        {{-- Status badge for user (below bell icon) --}}
        @if(!$isDirektur)
            @if($perjanjian->rejected)
                {{-- Status badge di bawah bell --}}
                <div class="status-badge" style="background:#DC3545;color:#fff;">
                    @if(empty($for_pdf) || !$for_pdf)
                        <i class="fa-solid fa-circle-xmark"></i>
                    @else
                        <span>✕</span>
                    @endif
                    <span>Perjanjian Kinerja Ditolak</span>
                </div>
                {{-- Modal untuk menampilkan alasan penolakan --}}
                <div id="modal-alasan-tolak"
                    style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;"
                    onclick="if(event.target === this) this.style.display='none';">
                    <div class="modal-content"
                        style="background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.25);padding:0;overflow:hidden;border:2px solid #e0e0e0;width:420px;max-width:calc(100vw - 40px);"
                        onclick="event.stopPropagation();">
                        <button onclick="document.getElementById('modal-alasan-tolak').style.display='none';"
                            style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:28px;font-weight:bold;color:#888;cursor:pointer;line-height:1;z-index:10;">&times;</button>

                        {{-- Header modal dengan info penolakan --}}
                        <div style="background:#f8f9fa;padding:20px 24px;border-bottom:1px solid #dee2e6;">
                            <div style="margin-bottom:8px;display:flex;align-items:flex-start;">
                                <span
                                    style="font-weight:600;color:#333;min-width:85px;display:inline-block;flex-shrink:0;">Status</span>
                                <span style="font-weight:600;color:#333;margin:0 8px;flex-shrink:0;">:</span>
                                <span style="font-weight:600;color:#DC3545;flex:1;word-wrap:break-word;">Ditolak</span>
                            </div>
                            <div style="margin-bottom:8px;display:flex;align-items:flex-start;">
                                <span
                                    style="font-weight:600;color:#333;min-width:85px;display:inline-block;flex-shrink:0;">Dari</span>
                                <span style="font-weight:600;color:#333;margin:0 8px;flex-shrink:0;">:</span>
                                <span
                                    style="color:#333;flex:1;word-wrap:break-word;">{{ $perjanjian->pihak2_name ?? 'dr. ARMA ROOSALINA, M.Kes' }}</span>
                            </div>
                            <div style="margin-bottom:8px;display:flex;align-items:flex-start;">
                                <span
                                    style="font-weight:600;color:#333;min-width:85px;display:inline-block;flex-shrink:0;">Jabatan</span>
                                <span style="font-weight:600;color:#333;margin:0 8px;flex-shrink:0;">:</span>
                                <span
                                    style="color:#333;flex:1;word-wrap:break-word;">{{ $perjanjian->pihak2_jabatan ?? 'Direktur UOBK RSUD Bangil' }}</span>
                            </div>
                            <div style="display:flex;align-items:flex-start;">
                                <span
                                    style="font-weight:600;color:#333;min-width:85px;display:inline-block;flex-shrink:0;">Tanggal</span>
                                <span style="font-weight:600;color:#333;margin:0 8px;flex-shrink:0;">:</span>
                                <span
                                    style="color:#333;flex:1;word-wrap:break-word;">{{ \Carbon\Carbon::parse($perjanjian->agreement_date ?? $perjanjian->created_at)->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>

                        {{-- Body modal dengan alasan --}}
                        <div style="padding:24px;">
                            <div
                                style="background:#fff;border:1px solid #f8d7da;border-left:4px solid #DC3545;border-radius:6px;padding:20px;margin-bottom:20px;">
                                <div style="display:flex;align-items:flex-start;gap:12px;">
                                    <span style="color:#DC3545;font-size:20px;margin-top:2px;">ℹ</span>
                                    <div style="flex:1;">
                                        <div style="font-weight:700;color:#333;margin-bottom:12px;font-size:15px;">Alasan
                                            penolakan :</div>
                                        <div style="color:#333;line-height:1.6;font-size:16px;font-weight:500;">
                                            {{ $perjanjian->rejection_reason ?? 'Target kinerja tidak sesuai dari perjanjian sebelumnya. harap revisi bagian target di indikator kinerja individu.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="text-align:center;">
                                <a href="{{ route('perjanjian.edit', $perjanjian->id) }}"
                                    style="display:inline-block;background:#0DA45C;color:#fff;padding:12px 36px;border:none;border-radius:8px;font-weight:700;font-size:15px;text-decoration:none;box-shadow:0 2px 4px rgba(13,164,92,0.3);">Revisi
                                    Perjanjian</a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(!empty($perjanjian->pihak2_signature))
                <div class="status-badge" style="background:#28a745;color:#fff;">
                    @if(empty($for_pdf) || !$for_pdf)
                        <i class="fa-solid fa-circle-check"></i>
                    @else
                        <span>✓</span>
                    @endif
                    <span>Perjanjian Disetujui</span>
                </div>
            @else
                <div class="status-badge" style="background:#ffc107;color:#222;">
                    @if(empty($for_pdf) || !$for_pdf)
                        <i class="fa-solid fa-clock"></i>
                    @else
                        🕐
                    @endif
                    <span>Menunggu Persetujuan</span>
                </div>
            @endif
        @endif

        {{-- Status badge for direktur (no bell icon) --}}
        @if($isDirektur)
            @if($status === 'ditolak' || $perjanjian->rejected)
                <div class="status-badge" style="background:#DC3545;color:#fff;">
                    @if(empty($for_pdf) || !$for_pdf)
                        <i class="fa-solid fa-circle-xmark"></i>
                    @else
                        <span>✕</span>
                    @endif
                    <span>Perjanjian Kinerja Ditolak</span>
                </div>
            @elseif($status === 'disetujui' || !empty($perjanjian->pihak2_signature))
                <div class="status-badge" style="background:#28a745;color:#fff;">
                    @if(empty($for_pdf) || !$for_pdf)
                        <i class="fa-solid fa-circle-check"></i>
                    @else
                        <span>✓</span>
                    @endif
                    <span>Perjanjian Disetujui</span>
                </div>
            @else
                {{-- Menunggu Persetujuan - no status badge shown, actions shown in header instead --}}
            @endif
        @endif

        <div class="preview-card">

            <!-- PAGE 1: COVER & IDENTITAS -->
            <div class="page" style="font-size: 11pt;">
                <div class="header" style="text-align:center; margin-bottom:0;">
                    @if(!empty($for_pdf) && !empty($logo_data))
                        <img src="{{ $logo_data }}" class="logo" alt="Logo Pemda"
                            style="max-height:50px;width:auto;display:inline-block;margin-bottom:2px;">
                    @else
                        <img src="{{ $logoPemda ?? asset('images/logo_pemda.png') }}" class="logo" alt="Logo Pemda"
                            loading="lazy" style="max-height:50px;width:auto;display:inline-block;margin-bottom:2px;"
                            onerror="this.style.display='none'">
                    @endif
                    <div style="font-weight:bold;font-size:12pt;margin-bottom:1px;">PEMERINTAH KABUPATEN PASURUAN</div>
                    <div style="font-weight:bold;font-size:12pt;margin-bottom:1px;">PERJANJIAN KINERJA TAHUN {{ $tahun ?? '2025' }}</div>
                    <div style="font-weight:bold;font-size:12pt;margin-bottom:0;">UOBK RSUD BANGIL</div>
                </div>
                <div class="content-section" style="text-align:justify; line-height:1.3; width: 90%; !important">
                    <div style="margin:0 0 6px 0;">Dalam rangka mewujudkan manajemen pemerintahan yang efektif,
                        transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :</div>
                    <div style="margin-bottom:4px; text-align:left;">
                        <span style="display:inline-block;width:100px;">Nama</span>:
                        {{ $perjanjian->pihak1_name ?? '-' }}
                    </div>
                    <div style="margin-bottom:4px; text-align:left;">
                        <span style="display:inline-block;width:100px;">Jabatan</span>:
                        {{ $perjanjian->pihak1_jabatan ?? '-' }}
                    </div>
                    <div style="margin-bottom:4px;">Selanjutnya disebut pihak pertama.</div>
                    <div style="margin-bottom:4px; text-align:left;">
                        <span style="display:inline-block;width:100px;">Nama</span>:
                        {{ $perjanjian->pihak2_name ?? '-' }}
                    </div>
                    <div style="margin-bottom:4px; text-align:left;">
                        <span style="display:inline-block;width:100px;">Jabatan</span>:
                        {{ $perjanjian->pihak2_jabatan ?? '-' }}
                    </div>
                    <div style="margin-bottom:4px;">Selaku atasan pihak pertama, selanjutnya disebut pihak kedua.</div>
                    <div style="margin-bottom:6px;">Pihak pertama berjanji akan mewujudkan target kinerja yang
                        seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah
                        seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian
                        target kinerja tersebut menjadi tanggung jawab kami.</div>
                    <div style="margin-bottom:10px;">Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari
                        perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan
                        sanksi.</div>

                    {{-- TTD --}}

                    <div class="sig-flex-row" style="display:flex;justify-content:space-between; margin-top:5px;">
                        <div class="sig-flex-col" style="text-align:center;width:45%;">
                            PIHAK KEDUA<br><br>
                            @if(!empty($for_pdf) && !empty($pihak2_ttd_data))
                                <img src="{{ $pihak2_ttd_data }}" style="max-height:50px;margin:2px 0;" alt="TTD Pihak 2">
                            @elseif(!empty($perjanjian->pihak2_signature))
                                <img src="{{ $perjanjian->pihak2_signature }}" style="max-height:50px;margin:2px 0;"
                                    alt="TTD Pihak 2" loading="lazy">
                            @elseif(!empty($perjanjian->pihak2_ttd_path))
                                <img src="{{ asset('storage/' . $perjanjian->pihak2_ttd_path) }}"
                                    style="max-height:50px;margin:2px 0;" alt="TTD Pihak 2" loading="lazy">
                            @else
                                <div style="height:50px;"></div>
                            @endif
                            <br>
                            <u>{{ $perjanjian->pihak2_name ?? '-' }}</u><br>
                            {{ $perjanjian->pihak2_pangkat ?? '-' }}<br>
                            NIP. {{ $perjanjian->pihak2_nip ?? '-' }}
                        </div>
                        <div class="sig-flex-col" style="text-align:center;width:45%;">
                            Pasuruan,
                            {{ Carbon\Carbon::parse($perjanjian->agreement_date ?? $perjanjian->created_at)->translatedFormat('d F Y') }}<br>
                            PIHAK PERTAMA<br>
                            @if(!empty($for_pdf) && !empty($pihak1_ttd_data))
                                <img src="{{ $pihak1_ttd_data }}" style="max-height:50px;margin:2px 0;" alt="TTD Pihak 1">
                            @elseif(!empty($perjanjian->pihak1_ttd))
                                <img src="{{ $perjanjian->pihak1_ttd }}" style="max-height:50px;margin:2px 0;"
                                    alt="TTD Pihak 1" loading="lazy">
                            @else
                                <div style="height:50px;"></div>
                            @endif
                            <br>
                            <u>{{ $perjanjian->pihak1_name ?? '-' }}</u><br>
                            {{ $perjanjian->pihak1_pangkat ?? '-' }}<br>
                            NIP. {{ $perjanjian->pihak1_nip ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>

            @php
                // Cek apakah tabelA (Indikator Kinerja) memiliki data valid
                $hasTabelAData = false;
                if (!empty($tabelA)) {
                    if (isset($tabelA['sasaran']) && is_array($tabelA['sasaran'])) {
                        foreach ($tabelA['sasaran'] as $idx => $sasaran) {
                            if (!empty($sasaran) || !empty($tabelA['indikator'][$idx] ?? '') || !empty($tabelA['target'][$idx] ?? '')) {
                                $hasTabelAData = true;
                                break;
                            }
                        }
                    } elseif (is_array($tabelA) && isset($tabelA[0])) {
                        foreach ($tabelA as $row) {
                            if (!empty($row['sasaran'] ?? '') || !empty($row['indikator'] ?? '') || !empty($row['target'] ?? '')) {
                                $hasTabelAData = true;
                                break;
                            }
                        }
                    }
                }

                // Cek apakah tabel program memiliki data valid sebelum ditampilkan
                $hasProgramData = false;
                $hasBudgetValue = function ($value) {
                    $num = (int) preg_replace('/[^0-9]/', '', (string) $value);
                    return $num > 0;
                };
                if (!empty($tabelC)) {
                    // Cek format hierarchical
                    if (isset($tabelC['programs']) && is_array($tabelC['programs'])) {
                        foreach ($tabelC['programs'] as $program) {
                            if ($hasBudgetValue($program['amount'] ?? null)) {
                                $hasProgramData = true;
                                break;
                            }
                        }
                    }
                    // Cek format flat lama
                    elseif (isset($tabelC['program']) && is_array($tabelC['program'])) {
                        foreach ($tabelC['program'] as $idx => $progName) {
                            if ($hasBudgetValue($tabelC['anggaran'][$idx] ?? null)) {
                                $hasProgramData = true;
                                break;
                            }
                        }
                    }
                }
            @endphp

            @if($hasTabelAData || $hasProgramData)
                <!-- PAGE 2: INDIKATOR KINERJA, TUGAS, FUNGSI, TABEL -->
                <div class="page">
                    <div class="header" style="text-align:center;">
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">INDIKATOR KINERJA INDIVIDU</div>
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">UOBK RSUD BANGIL</div>
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">TAHUN {{ $tahun ?? '2025' }}</div>
                    </div>
                    <div class="content-section" style="margin-top:10px;">
                        @php
                            $tugasValue = $perjanjian->tugas_pelaksana;
                            if ($tugasValue === null || $tugasValue === '') {
                                $tugasValue = $perjanjian->tugas ?: '-';
                            }
                            $fungsiValue = $perjanjian->fungsi_pelaksana;
                            if ($fungsiValue === null || $fungsiValue === '') {
                                $fungsiValue = $perjanjian->fungsi ?: '-';
                            }
                        @endphp

                        <!-- TABLE TANPA BORDER UNTUK JABATAN, TUGAS, FUNGSI -->
                        <table
                            style="width:90%;border-collapse:collapse;border:0;outline:0;border-spacing:0;margin-bottom:8px;line-height:1.15;">
                            <!-- JABATAN -->
                            <tr style="border:0 !important;">
                                <td
                                    style="width:80px;padding:6px 0;font-weight:500;vertical-align:top;border:none !important;">
                                    Jabatan</td>
                                <td style="width:10px;padding:6px 6px;text-align:center;border:none !important;">:</td>
                                <td style="padding:6px 0;vertical-align:top;border:none !important;">
                                    {{ $perjanjian->pihak1_jabatan ?? '-' }}
                                </td>
                            </tr>

                            <!-- TUGAS -->
                            <tr style="border:0 !important;">
                                <td
                                    style="width:80px;padding:6px 0;font-weight:500;vertical-align:top;border:none !important;">
                                    Tugas</td>
                                <td style="width:10px;padding:6px 6px;text-align:center;border:none !important;">:</td>
                                <td
                                    style="padding:6px 0;vertical-align:top;text-align:justify;line-height:1.15;border:none !important;">
                                    {!! nl2br(e($tugasValue)) !!}
                                </td>
                            </tr>

                            <!-- FUNGSI -->
                            <tr style="border:0 !important;">
                                <td
                                    style="width:80px;padding:6px 0;font-weight:500;vertical-align:top;border:none !important;">
                                    Fungsi</td>
                                <td style="width:10px;padding:6px 6px;text-align:center;border:none !important;">:</td>
                                <td style="padding:6px 0;vertical-align:top;line-height:1.15;border:none !important;">
                                    @php
                                        // Build a clean lower-alpha list from fungsi value
                                        $fungsiItems = [];
                                        if (is_array($fungsiValue)) {
                                            $fungsiItems = $fungsiValue;
                                        } elseif (is_string($fungsiValue)) {
                                            $decoded = json_decode($fungsiValue, true);
                                            if (is_array($decoded)) {
                                                $fungsiItems = $decoded;
                                            } else {
                                                // fallback split by newline
                                                $fungsiItems = preg_split("/\r\n|\n|\r/", $fungsiValue);
                                            }
                                        }

                                        // Normalize: trim items and drop empties
                                        $fungsiItems = array_values(array_filter(array_map(function ($s) {
                                            return is_string($s) ? trim($s) : '';
                                        }, (array) $fungsiItems), function ($s) {
                                            return $s !== '';
                                        }));

                                        // If it's explicitly a single dash, show '-' without numbering
                                        $rawFungsiStr = is_string($fungsiValue) ? trim($fungsiValue) : '';
                                        if ($rawFungsiStr === '-' || (count($fungsiItems) === 1 && $fungsiItems[0] === '-')) {
                                            $fungsiItems = [];
                                            $fungsiValue = '-';
                                        }
                                    @endphp
                                    @if(!empty($fungsiItems))
                                        <ol style="margin:0;padding-left:18px;list-style-type:lower-alpha;line-height:1.15;">
                                            @foreach($fungsiItems as $fi)
                                                @php
                                                    // Remove any existing alphabetical prefix like "a. " to avoid duplication
                                                    $cleanFi = preg_replace('/^[a-zA-Z]\.\s*/', '', trim((string) $fi));
                                                @endphp
                                                <li style="margin-bottom:3px;text-align:justify;">{{ $cleanFi }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <div class="table-responsive">
                            <table class="table table-fixed" style="table-layout: fixed; width: 90%;">
                                <thead>
                                    <tr>
                                        <th style="width:25px;">NO</th>
                                        <th style="width:38%;">SASARAN</th>
                                        <th style="width:38%;">INDIKATOR KINERJA</th>
                                        <th style="width:10%;">SATUAN</th>
                                        <th style="width:9%;">TARGET</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sasaranData = [];
                                        if (isset($tabelA['sasaran']) && is_array($tabelA['sasaran'])) {
                                            $count = count($tabelA['sasaran']);
                                            for ($i = 0; $i < $count; $i++) {
                                                $sasaranData[] = [
                                                    'sasaran' => $tabelA['sasaran'][$i] ?? '',
                                                    'indikator' => $tabelA['indikator'][$i] ?? '',
                                                    'satuan' => $tabelA['satuan'][$i] ?? '',
                                                    'target' => $tabelA['target'][$i] ?? ''
                                                ];
                                            }
                                        } elseif (is_array($tabelA) && isset($tabelA[0]) && is_array($tabelA[0])) {
                                            $sasaranData = $tabelA;
                                        }
                                    @endphp
                                    @forelse($sasaranData as $i => $row)
                                        <tr>
                                            <td style="text-align:center;">{{ (int) $i + 1 }}</td>
                                            <td>{{ $row['sasaran'] ?? '' }}</td>
                                            <td>{{ $row['indikator'] ?? '' }}</td>
                                            <td style="text-align:center;">{{ $row['satuan'] ?? '' }}</td>
                                            <td style="text-align:center;">{{ $row['target'] ?? '' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="no-data">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($hasProgramData)
                            <div class="table-responsive">
                                <table class="table table-fixed" style="table-layout: fixed; width: 90%;">
                                    <thead>
                                        <tr>
                                            <th style="width:25px;">No</th>
                                            <th style="width:50%;">Program</th>
                                            <th style="width:20%;">Anggaran</th>
                                            <th style="width:25%;">Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Fungsi untuk flatten struktur hierarchical program sekaligus numbering dan total
                                            // Gunakan function_exists untuk mencegah "Cannot redeclare" error
                                            if (!function_exists('flattenProgramHierarchy')) {
                                                function flattenProgramHierarchy($tabelC)
                                                {
                                                    $flatList = [];
                                                    $programTotal = 0;

                                                    if (empty($tabelC)) {
                                                        return ['rows' => $flatList, 'total' => $programTotal];
                                                    }

                                                    // Format 1: Hierarchical dengan 'programs' array (format baru)
                                                    if (isset($tabelC['programs']) && is_array($tabelC['programs']) && !empty($tabelC['programs'])) {
                                                        foreach ($tabelC['programs'] as $pIdx => $program) {
                                                            $programNo = ($pIdx + 1);
                                                            $pAmount = (int) preg_replace('/[^0-9]/', '', (string) ($program['amount'] ?? '0'));
                                                            if (!empty($program['name']) || $pAmount > 0) {
                                                                $flatList[] = [
                                                                    'no' => $programNo,
                                                                    'name' => $program['name'] ?? '',
                                                                    'amount' => $pAmount,
                                                                    'source' => isset($program['source']) && $program['source'] !== '-' ? $program['source'] : '',
                                                                ];
                                                                $programTotal += $pAmount;
                                                            }

                                                            // Tambah kegiatan
                                                            if (isset($program['kegiatan']) && is_array($program['kegiatan'])) {
                                                                foreach ($program['kegiatan'] as $kIdx => $kegiatan) {
                                                                    $kegiatanNo = $programNo . '.' . ($kIdx + 1);
                                                                    $kAmount = (int) preg_replace('/[^0-9]/', '', (string) ($kegiatan['amount'] ?? '0'));
                                                                    if (!empty($kegiatan['name']) || $kAmount > 0) {
                                                                        $flatList[] = [
                                                                            'no' => $kegiatanNo,
                                                                            'name' => $kegiatan['name'] ?? '',
                                                                            'amount' => $kAmount,
                                                                            'source' => isset($kegiatan['source']) && $kegiatan['source'] !== '-' ? $kegiatan['source'] : '',
                                                                        ];
                                                                    }

                                                                    // Tambah sub-kegiatan
                                                                    if (isset($kegiatan['subKegiatan']) && is_array($kegiatan['subKegiatan'])) {
                                                                        foreach ($kegiatan['subKegiatan'] as $sIdx => $subKegiatan) {
                                                                            $subNo = $kegiatanNo . '.' . ($sIdx + 1);
                                                                            $sAmount = (int) preg_replace('/[^0-9]/', '', (string) ($subKegiatan['amount'] ?? '0'));
                                                                            if (!empty($subKegiatan['name']) || $sAmount > 0) {
                                                                                $flatList[] = [
                                                                                    'no' => $subNo,
                                                                                    'name' => $subKegiatan['name'] ?? '',
                                                                                    'amount' => $sAmount,
                                                                                    'source' => isset($subKegiatan['source']) && $subKegiatan['source'] !== '-' ? $subKegiatan['source'] : '',
                                                                                ];
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        return ['rows' => $flatList, 'total' => $programTotal];
                                                    }

                                                    // Format 2: Flat lama dengan 'program', 'anggaran', 'keterangan' arrays (format lama)
                                                    if (isset($tabelC['program']) && is_array($tabelC['program'])) {
                                                        $count = count($tabelC['program']);
                                                        for ($i = 0; $i < $count; $i++) {
                                                            $name = $tabelC['program'][$i] ?? '';
                                                            $amt = (int) preg_replace('/[^0-9]/', '', (string) ($tabelC['anggaran'][$i] ?? '0'));
                                                            if (!empty($name) || $amt > 0) {
                                                                $flatList[] = [
                                                                    'no' => $i + 1,
                                                                    'name' => $name,
                                                                    'amount' => $amt,
                                                                    'source' => isset($tabelC['keterangan'][$i]) && $tabelC['keterangan'][$i] !== '-' ? (string) $tabelC['keterangan'][$i] : '',
                                                                ];
                                                                $programTotal += $amt;
                                                            }
                                                        }
                                                        return ['rows' => $flatList, 'total' => $programTotal];
                                                    }

                                                    return ['rows' => $flatList, 'total' => $programTotal];
                                                }
                                            } // end function_exists check

                                            $programResult = flattenProgramHierarchy($tabelC);
                                            $programData = $programResult['rows'];
                                            $programTotal = $programResult['total'];
                                        @endphp
                                        @forelse($programData as $i => $row)
                                            <tr>
                                                <td style="text-align:center;">{{ $row['no'] ?? ($i + 1) }}</td>
                                                <td>{{ $row['name'] ?? '' }}</td>
                                                <td style="text-align:right;">{{ number_format((int) $row['amount'], 0, ',', '.') }}
                                                </td>
                                                <td style="font-size:11px; word-wrap:break-word; white-space:normal;">
                                                    {{ $row['source'] ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="no-data">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if(($programTotal ?? 0) > 0)
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" style="text-align:right;font-weight:700;">Total</td>
                                                <td style="text-align:right;font-weight:700;">
                                                    {{ number_format((int) $programTotal, 0, ',', '.') }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        @endif

                        {{-- Signature dengan repeat header jika pindah halaman --}}
                        <div style="margin-top:10px;">
                            {{-- Repeat header jika signature ada di halaman baru --}}
                            <div class="header-repeat" style="display:none;">
                                <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;text-align:center;">RENCANA
                                    ANGGARAN BIAYA</div>
                                <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;text-align:center;">
                                    {{ strtoupper($perjanjian->pihak1_jabatan ?? '-') }}
                                </div>
                                <div style="font-weight:bold;font-size:1.1rem;margin-bottom:20px;text-align:center;">TAHUN
                                    {{ $tahun ?? '2025' }}
                                </div>
                            </div>

                            <div class="sig-flex-row" style="display:flex;justify-content:space-between;">
                                <div class="sig-flex-col" style="text-align:center;width:45%;">
                                    PIHAK KEDUA<br><br>
                                    @if(!empty($for_pdf) && !empty($pihak2_ttd_data))
                                        <img src="{{ $pihak2_ttd_data }}" style="max-height:60px;margin:5px 0;"
                                            alt="TTD Pihak 2">
                                    @elseif(!empty($perjanjian->pihak2_signature))
                                        <img src="{{ $perjanjian->pihak2_signature }}" style="max-height:60px;margin:5px 0;"
                                            alt="TTD Pihak 2" loading="lazy">
                                    @elseif(!empty($perjanjian->pihak2_ttd_path))
                                        <img src="{{ asset('storage/' . $perjanjian->pihak2_ttd_path) }}"
                                            style="max-height:60px;margin:5px 0;" alt="TTD Pihak 2" loading="lazy">
                                    @else
                                        <div style="height:60px;"></div>
                                    @endif
                                    <br>
                                    <u>{{ $perjanjian->pihak2_name ?? '-' }}</u><br>
                                    {{ $perjanjian->pihak2_pangkat ?? '-' }}<br>
                                    NIP. {{ $perjanjian->pihak2_nip ?? '-' }}
                                </div>
                                <div class="sig-flex-col" style="text-align:center;width:45%;">
                                    Pasuruan,
                                    {{ Carbon\Carbon::parse($perjanjian->agreement_date ?? $perjanjian->created_at)->translatedFormat('d F Y') }}<br>
                                    PIHAK PERTAMA<br>
                                    @if(!empty($for_pdf) && !empty($pihak1_ttd_data))
                                        <img src="{{ $pihak1_ttd_data }}" style="max-height:60px;margin:5px 0;"
                                            alt="TTD Pihak 1">
                                    @elseif(!empty($perjanjian->pihak1_ttd))
                                        <img src="{{ $perjanjian->pihak1_ttd }}" style="max-height:60px;margin:5px 0;"
                                            alt="TTD Pihak 1" loading="lazy">
                                    @else
                                        <div style="height:60px;"></div>
                                    @endif
                                    <br>
                                    <u>{{ $perjanjian->pihak1_name ?? '-' }}</u><br>
                                    {{ $perjanjian->pihak1_pangkat ?? '-' }}<br>
                                    NIP. {{ $perjanjian->pihak1_nip ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @php
                // Cek apakah tabelB (Rencana Aksi) memiliki data valid
                // Cek sederhana: apakah tabelB memiliki key 'sasaran' dan minimal ada 1 elemen
                $hasTabelBData = false;
                if (isset($tabelB) && is_array($tabelB) && !empty($tabelB)) {
                    if (isset($tabelB['sasaran']) && is_array($tabelB['sasaran']) && count($tabelB['sasaran']) > 0) {
                        $hasTabelBData = true;
                    } elseif (isset($tabelB[0])) {
                        $hasTabelBData = true;
                    }
                }

                // Cek apakah tabel D (Anggaran Triwulan) memiliki data valid
                $hasTabelDData = false;
                $hasBudgetValue = function ($value) {
                    $num = (int) preg_replace('/[^0-9]/', '', (string) $value);
                    return $num > 0;
                };
                if (isset($tabelC) && is_array($tabelC) && !empty($tabelC)) {
                    // Format hierarchical
                    if (isset($tabelC['programs']) && is_array($tabelC['programs'])) {
                        foreach ($tabelC['programs'] as $program) {
                            $programHasData = $hasBudgetValue($program['amount'] ?? null)
                                || $hasBudgetValue($program['tw1'] ?? null)
                                || $hasBudgetValue($program['tw2'] ?? null)
                                || $hasBudgetValue($program['tw3'] ?? null)
                                || $hasBudgetValue($program['tw4'] ?? null);
                            if ($programHasData) {
                                $hasTabelDData = true;
                                break;
                            }
                            if (isset($program['kegiatan']) && is_array($program['kegiatan'])) {
                                foreach ($program['kegiatan'] as $kegiatan) {
                                    $kegiatanHasData = $hasBudgetValue($kegiatan['amount'] ?? null)
                                        || $hasBudgetValue($kegiatan['tw1'] ?? null)
                                        || $hasBudgetValue($kegiatan['tw2'] ?? null)
                                        || $hasBudgetValue($kegiatan['tw3'] ?? null)
                                        || $hasBudgetValue($kegiatan['tw4'] ?? null);
                                    if ($kegiatanHasData) {
                                        $hasTabelDData = true;
                                        break 2;
                                    }
                                    if (isset($kegiatan['subKegiatan']) && is_array($kegiatan['subKegiatan'])) {
                                        foreach ($kegiatan['subKegiatan'] as $subKegiatan) {
                                            $subHasData = $hasBudgetValue($subKegiatan['amount'] ?? null)
                                                || $hasBudgetValue($subKegiatan['tw1'] ?? null)
                                                || $hasBudgetValue($subKegiatan['tw2'] ?? null)
                                                || $hasBudgetValue($subKegiatan['tw3'] ?? null)
                                                || $hasBudgetValue($subKegiatan['tw4'] ?? null);
                                            if ($subHasData) {
                                                $hasTabelDData = true;
                                                break 3;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // Format flat lama
                    elseif (isset($tabelC['program']) && is_array($tabelC['program'])) {
                        $count = count($tabelC['program']);
                        for ($i = 0; $i < $count; $i++) {
                            $hasRow = $hasBudgetValue($tabelC['anggaran'][$i] ?? null)
                                || $hasBudgetValue($tabelC['tw1'][$i] ?? null)
                                || $hasBudgetValue($tabelC['tw2'][$i] ?? null)
                                || $hasBudgetValue($tabelC['tw3'][$i] ?? null)
                                || $hasBudgetValue($tabelC['tw4'][$i] ?? null);
                            if ($hasRow) {
                                $hasTabelDData = true;
                                break;
                            }
                        }
                    }
                }
            @endphp

            @if($hasTabelBData || $hasTabelDData)
                <!-- PAGE 3: RENCANA AKSI -->
                <div class="page-landscape" style="background-color: #fff;">
                    <div class="header" style="text-align:center;">
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">RENCANA AKSI</div>
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">
                            {{ strtoupper($perjanjian->pihak1_jabatan ?? '-') }}
                        </div>
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">UOBK RSUD BANGIL KABUPATEN
                            PASURUAN</div>
                        <div style="font-weight:bold;font-size:1.1rem;margin-bottom:2px;">TAHUN {{ $tahun ?? '2025' }}</div>
                    </div>
                    <div class="content-section" style="margin-top:10px;">
                        @if($hasTabelBData)
                            <div class="table-responsive">
                                <table class="table table-fixed" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width:25px;">No</th>
                                            <th style="width:28%;">Sasaran</th>
                                            <th style="width:26%;">Indikator Kinerja</th>
                                            <th style="width:8%;">Target</th>
                                            <th style="width:9%;">Triwulan I</th>
                                            <th style="width:9%;">Triwulan II</th>
                                            <th style="width:9%;">Triwulan III</th>
                                            <th style="width:9%;">Triwulan IV</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rencanaAksiData = [];
                                            if (isset($tabelB['sasaran']) && is_array($tabelB['sasaran'])) {
                                                $count = count($tabelB['sasaran']);
                                                for ($i = 0; $i < $count; $i++) {
                                                    $rencanaAksiData[] = [
                                                        'sasaran' => $tabelB['sasaran'][$i] ?? '',
                                                        'indikator' => $tabelB['indikator'][$i] ?? '',
                                                        'target' => $tabelB['target'][$i] ?? '',
                                                        'tw1' => $tabelB['tw1'][$i] ?? '',
                                                        'tw2' => $tabelB['tw2'][$i] ?? '',
                                                        'tw3' => $tabelB['tw3'][$i] ?? '',
                                                        'tw4' => $tabelB['tw4'][$i] ?? ''
                                                    ];
                                                }
                                            } elseif (is_array($tabelC) && isset($tabelC[0]) && is_array($tabelC[0]) && isset($tabelC[0]['sasaran'])) {
                                                $rencanaAksiData = $tabelC;
                                            }
                                        @endphp
                                        @forelse($rencanaAksiData as $i => $row)
                                            <tr>
                                                <td style="text-align:center;">{{ (int) $i + 1 }}</td>
                                                <td>{{ $row['sasaran'] ?? '' }}</td>
                                                <td>{{ $row['indikator'] ?? '' }}</td>
                                                <td style="text-align:center;">{{ $row['target'] ?? '' }}</td>
                                                <td style="text-align:right;">{{ !empty($row['tw1']) ? $row['tw1'] : '' }}</td>
                                                <td style="text-align:right;">{{ !empty($row['tw2']) ? $row['tw2'] : '' }}</td>
                                                <td style="text-align:right;">{{ !empty($row['tw3']) ? $row['tw3'] : '' }}</td>
                                                <td style="text-align:right;">{{ !empty($row['tw4']) ? $row['tw4'] : '' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="no-data">Tidak ada data rencana aksi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($hasTabelDData)
                            @php 
                                                                                                                                                                                                                                                    // Fungsi untuk flatten struktur hierarchical Tabel Ke-4 dengan numbering dan total program saja
                                // Gunakan function_exists untuk mencegah "Cannot redeclare" error
                                if (!function_exists('flattenHierarchicalBudget')) {
                                    function flattenHierarchicalBudget($tabelC)
                                    {
                                        $flatList = [];
                                        $programTotal = 0;

                                        if (isset($tabelC['programs']) && is_array($tabelC['programs']) && !empty($tabelC['programs'])) {
                                            foreach ($tabelC['programs'] as $pIdx => $program) {
                                                $programNo = ($pIdx + 1);
                                                $pAmount = (int) preg_replace('/[^0-9]/', '', (string) ($program['amount'] ?? '0'));

                                                // Aggregate tw1-tw4 from kegiatan if not set at program level
                                                $programTw1 = $program['tw1'] ?? 0;
                                                $programTw2 = $program['tw2'] ?? 0;
                                                $programTw3 = $program['tw3'] ?? 0;
                                                $programTw4 = $program['tw4'] ?? 0;

                                                if (empty($programTw1) && isset($program['kegiatan']) && is_array($program['kegiatan'])) {
                                                    foreach ($program['kegiatan'] as $keg) {
                                                        $programTw1 += floatval($keg['tw1'] ?? 0);
                                                        $programTw2 += floatval($keg['tw2'] ?? 0);
                                                        $programTw3 += floatval($keg['tw3'] ?? 0);
                                                        $programTw4 += floatval($keg['tw4'] ?? 0);
                                                    }
                                                }

                                                if (!empty($program['name']) || $pAmount > 0) {
                                                    $flatList[] = [
                                                        'no' => $programNo,
                                                        'level' => 'program',
                                                        'name' => $program['name'] ?? '',
                                                        'amount' => $pAmount,
                                                        'tw1' => $programTw1,
                                                        'tw2' => $programTw2,
                                                        'tw3' => $programTw3,
                                                        'tw4' => $programTw4,
                                                    ];
                                                    $programTotal += $pAmount;
                                                }

                                                if (isset($program['kegiatan']) && is_array($program['kegiatan'])) {
                                                    foreach ($program['kegiatan'] as $kIdx => $kegiatan) {
                                                        $kegiatanNo = $programNo . '.' . ($kIdx + 1);
                                                        $kAmount = (int) preg_replace('/[^0-9]/', '', (string) ($kegiatan['amount'] ?? '0'));
                                                        if (!empty($kegiatan['name']) || $kAmount > 0) {
                                                            $flatList[] = [
                                                                'no' => $kegiatanNo,
                                                                'level' => 'kegiatan',
                                                                'name' => $kegiatan['name'] ?? '',
                                                                'amount' => $kAmount,
                                                                'tw1' => $kegiatan['tw1'] ?? '',
                                                                'tw2' => $kegiatan['tw2'] ?? '',
                                                                'tw3' => $kegiatan['tw3'] ?? '',
                                                                'tw4' => $kegiatan['tw4'] ?? '',
                                                            ];
                                                        }

                                                        if (isset($kegiatan['subKegiatan']) && is_array($kegiatan['subKegiatan'])) {
                                                            foreach ($kegiatan['subKegiatan'] as $sIdx => $subKegiatan) {
                                                                $subNo = $kegiatanNo . '.' . ($sIdx + 1);
                                                                $sAmount = (int) preg_replace('/[^0-9]/', '', (string) ($subKegiatan['amount'] ?? '0'));
                                                                if (!empty($subKegiatan['name']) || $sAmount > 0) {
                                                                    $flatList[] = [
                                                                        'no' => $subNo,
                                                                        'level' => 'subkegiatan',
                                                                        'name' => $subKegiatan['name'] ?? '',
                                                                        'amount' => $sAmount,
                                                                        'tw1' => $subKegiatan['tw1'] ?? '',
                                                                        'tw2' => $subKegiatan['tw2'] ?? '',
                                                                        'tw3' => $subKegiatan['tw3'] ?? '',
                                                                        'tw4' => $subKegiatan['tw4'] ?? '',
                                                                    ];
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            return ['rows' => $flatList, 'total' => $programTotal];
                                        }

                                        // Fallback ke format flat lama
                                        if (isset($tabelC['program']) && is_array($tabelC['program'])) {
                                            $count = count($tabelC['program']);
                                            for ($i = 0; $i < $count; $i++) {
                                                $name = $tabelC['program'][$i] ?? '';
                                                $amt = (int) preg_replace('/[^0-9]/', '', (string) ($tabelC['anggaran'][$i] ?? '0'));
                                                if (!empty($name) || $amt > 0) {
                                                    $flatList[] = [
                                                        'no' => $i + 1,
                                                        'level' => 'program',
                                                        'name' => $name,
                                                        'amount' => $amt,
                                                        'tw1' => $tabelC['tw1'][$i] ?? '',
                                                        'tw2' => $tabelC['tw2'][$i] ?? '',
                                                        'tw3' => $tabelC['tw3'][$i] ?? '',
                                                        'tw4' => $tabelC['tw4'][$i] ?? '',
                                                    ];
                                                    $programTotal += $amt;
                                                }
                                            }
                                            return ['rows' => $flatList, 'total' => $programTotal];
                                        }

                                        return ['rows' => $flatList, 'total' => $programTotal];
                                    }
                                } // end function_exists check

                                $hierarchicalBudgetResult = flattenHierarchicalBudget($tabelC);
                                $programs = $hierarchicalBudgetResult['rows'];
                                $hierarchicalBudgetTotal = $hierarchicalBudgetResult['total'];
                            @endphp
                                <div class="table-responsive" style="margin-top:18px;">
                                    <table class="table table-fixed" style="table-layout: fixed; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width:25px;">No</th>
                                                <th style="width:35%;">Program</th>
                                                <th style="width:15%;">Anggaran</th>
                                                <th style="width:11%;">Triwulan I</th>
                                                <th style="width:11%;">Triwulan II</th>
                                                <th style="width:11%;">Triwulan III</th>
                                                <th style="width:11%;">Triwulan IV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($programs as $i => $row)
                                                <tr>
                                                    <td style="text-align:center;">{{ $row['no'] ?? ($i + 1) }}</td>
                                                    <td style="font-weight: {{ ($row['level'] ?? '') === 'program' ? 'bold' : 'normal' }}; font-style: {{ ($row['level'] ?? '') === 'kegiatan' ? 'italic' : 'normal' }};">{{ $row['name'] }}</td>
                                                    <td style="text-align:right;">{{ number_format((int) $row['amount'], 0, ',', '.') }}</td>
                                                    <td style="text-align:right; background-color: {{ empty($row['tw1']) ? '#f9f9f9' : 'transparent' }};">{{ !empty($row['tw1']) ? number_format((int) preg_replace('/[^0-9]/', '', (string) $row['tw1']), 0, ',', '.') : '' }}</td>
                                                    <td style="text-align:right; background-color: {{ empty($row['tw2']) ? '#f9f9f9' : 'transparent' }};">{{ !empty($row['tw2']) ? number_format((int) preg_replace('/[^0-9]/', '', (string) $row['tw2']), 0, ',', '.') : '' }}</td>
                                                    <td style="text-align:right; background-color: {{ empty($row['tw3']) ? '#f9f9f9' : 'transparent' }};">{{ !empty($row['tw3']) ? number_format((int) preg_replace('/[^0-9]/', '', (string) $row['tw3']), 0, ',', '.') : '' }}</td>
                                                    <td style="text-align:right; background-color: {{ empty($row['tw4']) ? '#f9f9f9' : 'transparent' }};">{{ !empty($row['tw4']) ? number_format((int) preg_replace('/[^0-9]/', '', (string) $row['tw4']), 0, ',', '.') : '' }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="no-data">
                                                    Tidak ada data program/kegiatan.<br>
                                                    <small style="font-size:10px;color:#666;">Silakan edit perjanjian dan isi Tabel D (Rencana Anggaran) dengan Program, Kegiatan, Sub Kegiatan, dan Target Triwulan.</small>
                                                </td></tr>
                                            @endforelse
                                        </tbody>
                                        @if(($hierarchicalBudgetTotal ?? 0) > 0)
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="text-align:right;font-weight:700;">Total</td>
                                                    <td style="text-align:right;font-weight:700;">{{ number_format((int) $hierarchicalBudgetTotal, 0, ',', '.') }}</td>
                                                    <td colspan="4"></td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                        @endif

                            {{-- Tanda tangan, pisah div sendiri --}}
                            <div class="signature-wrapper" style="margin-top: 20px;">
                                <div class="signature-block">
                                    Pasuruan, {{ Carbon\Carbon::parse($perjanjian->agreement_date ?? $perjanjian->created_at)->translatedFormat('d F Y') }}<br>
                                    {{ strtoupper($perjanjian->pihak1_jabatan ?? '-') }}<br><br>

                                    @if(!empty($for_pdf) && !empty($pihak1_ttd_data))
                                        <img src="{{ $pihak1_ttd_data }}" style="max-height:60px;margin:5px 0;" alt="TTD Pihak 1">
                                    @elseif(!empty($perjanjian->pihak1_ttd))
                                        <img src="{{ $perjanjian->pihak1_ttd }}" style="max-height:60px;margin:5px 0;" alt="TTD Pihak 1" loading="lazy">
                                    @else
                                        <div style="height:60px;"></div>
                                    @endif
                                    <br>

                                    <u>{{ $perjanjian->pihak1_name ?? '-' }}</u><br>
                                    {{ $perjanjian->pihak1_pangkat ?? '-' }}<br>
                                    NIP. {{ $perjanjian->pihak1_nip ?? '-' }}
                                </div>
                            </div>
                    </div>
                </div>
            @endif

    </div>{{-- End preview-card --}}
    </div>{{-- End preview-center-wrapper --}}

    {{-- Footer for user preview --}}
    @if(!$isDirektur)
        <div class="footer-fixed">
            © {{ date('Y') }} © 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil
        </div>
    @endif

    {{-- Footer for direktur preview --}}
    @if($isDirektur)
        <div class="footer-fixed">
            © {{ date('Y') }} © 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil
        </div>
    @endif

</body>
</html>