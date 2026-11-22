/* HaqDar v2 — dictionary and i18n helpers. Plain browser JS, no modules.
   Every visible string in the app comes from T. Keys are identical in en, hi, te. */

var T = {
  en: {
    app_name: 'HaqDar',
    page_title: 'HaqDar · Scholarships',
    trust_line: '{n} schemes · Verified {date} · No Aadhaar stored · Free',
    lang_title: 'Choose your language',
    lang_label: 'Language',
    lang_name_en: 'English',
    lang_name_hi: 'हिन्दी',
    lang_name_te: 'తెలుగు',
    tab_chat: 'Find',
    tab_results: 'Results',
    tab_apps: 'My Applications',
    opening_msg: 'Tell me about yourself — studies, family income, category. Type or tap the mic.',
    persona_en: 'I am a BC student, family income 1.5 lakh, B.Tech at a government college',
    persona_hi: 'मैं SC छात्र हूँ, परिवार की आय 2 लाख, सरकारी कॉलेज में B.Tech',
    persona_te: 'నేను BC విద్యార్థిని, కుటుంబ ఆదాయం 1.5 లక్షలు, ప్రభుత్వ కళాశాలలో B.Tech',
    placeholder_input: 'Type here… e.g. I am 19, SC, BCom',
    btn_send: 'Send',
    btn_mic: '🎙 Speak',
    mic_listening: 'Listening…',
    blocked_aadhaar: '⚠️ Never share Aadhaar — we removed it.',
    q_category: 'Which category are you in?',
    q_family_income: 'Roughly what is your annual family income?',
    q_education_level: 'What are you studying now?',
    q_gender: 'What is your gender?',
    q_institution_type: 'What type of institution do you study in?',
    btn_skip: 'Skip',
    cat_SC: 'SC', cat_ST: 'ST', cat_BC: 'BC', cat_EWS: 'EWS', cat_Minority: 'Minority', cat_OC: 'OC',
    inc_80000: 'Under ₹1 lakh', inc_200000: '₹1–3 lakh', inc_500000: '₹3–8 lakh', inc_1000000: 'Above ₹8 lakh',
    edu_class_8: 'Class 8', edu_class_9_10: 'Class 9–10', edu_intermediate: 'Intermediate (11–12)',
    edu_diploma: 'Diploma', edu_ug: 'Degree (UG)', edu_pg: 'Postgraduate', edu_phd: 'PhD',
    gen_male: 'Male', gen_female: 'Female',
    inst_government: 'Government', inst_aided: 'Aided', inst_private: 'Private',
    thinking: 'Thinking…',
    profile_title: 'What we understood',
    edit_chip_prefix: 'Edit',
    recheck_btn: 'Answer & recheck',
    f_age: 'Age', f_gender: 'Gender', f_category: 'Category', f_family_income: 'Family income',
    f_education_level: 'Education level', f_course: 'Course', f_institution_type: 'Institution type',
    f_state: 'State', f_disability: 'Disability', f_marks_percent: 'Marks %',
    not_given: 'not given', yes: 'yes', no: 'no',
    results_title: 'Your results',
    summary_full: 'You may be eligible for {x} schemes · next deadline in {n} days · likely, not guaranteed',
    summary_nodl: 'You may be eligible for {x} schemes · likely, not guaranteed',
    btn_read: '🔊 Read aloud',
    btn_stop_read: '⏹ Stop reading',
    btn_whatsapp: '📲 Share on WhatsApp',
    btn_print: '🖨️ Print checklist',
    wa_intro: 'Schemes I may be eligible for',
    wa_tagline: 'free, no middlemen, likely not guaranteed',
    print_title: 'Take this to your college / MeeSeva centre',
    status_likely: 'Likely', status_maybe: 'Maybe', status_no: 'No',
    likely_title: '✅ Likely eligible', maybe_title: '❓ Maybe', no_title: 'Not a match',
    no_results: 'No schemes matched yet. Tell us more about yourself.',
    rule_pass: '✅ pass', rule_fail: '❌ fail', rule_unknown: '❓ unknown',
    rule_line: '{label} · you: {value}',
    level_label: 'Level', provider_label: 'Provider', benefit_label: 'Benefit',
    btn_official: 'Official website', last_verified: 'Last verified', not_verified: 'not verified',
    deadline_none: 'no upcoming deadline', days_left: '{n} days left', days_red: 'Closing soon',
    docs_title: 'Documents to keep ready', steps_title: 'Steps',
    btn_track: 'Track this', btn_tracked: 'Tracked ✓', btn_calendar: 'Add to calendar',
    whatif_title: '💡 What would change this',
    needs_line: 'Needs: {label}', tellus_line: 'Tell us: {label}',
    err_prefix: 'Something went wrong: ', err_network: 'Could not reach the server. Please try again.',
    apps_title: 'My Applications', apps_sub: 'Track where each scheme stands',
    apps_empty: 'No applications tracked yet. Open a scheme and tap "Track this".',
    apps_stage: 'Application status',
    stat_not_started: 'Not started', stat_documents_ready: 'Documents ready', stat_applied: 'Applied',
    stat_under_verification: 'Under verification', stat_received: 'Received',
    footer_note: 'Free service. No middlemen needed. Final decision is by the scheme authority.',
    btn_how: 'How we decide',
    modal_title: 'How we decide',
    modal_1: 'AI only understands your words. It turns them into fields like age and category.',
    modal_2: 'Fixed, editable rules decide eligibility. The same answers always give the same result.',
    modal_3: 'Official sources, each with a verified date and an official link.',
    modal_4: 'We never store Aadhaar. Any Aadhaar number is removed before anything else happens.',
    btn_close: 'Close',
    link_partners: 'For partners',
    sec_likely_label: 'Likely eligible', sec_maybe_label: 'Maybe', sec_no_label: 'Not a match'
  },
  hi: {
    app_name: 'HaqDar',
    page_title: 'HaqDar · छात्रवृत्ति',
    trust_line: '{n} योजनाएँ · {date} को सत्यापित · कोई आधार संग्रहित नहीं · निःशुल्क',
    lang_title: 'अपनी भाषा चुनें',
    lang_label: 'भाषा',
    lang_name_en: 'English',
    lang_name_hi: 'हिन्दी',
    lang_name_te: 'తెలుగు',
    tab_chat: 'खोजें',
    tab_results: 'परिणाम',
    tab_apps: 'मेरे आवेदन',
    opening_msg: 'अपने बारे में बताइए — पढ़ाई, परिवार की आय, वर्ग। लिखें या माइक दबाएँ।',
    persona_en: 'I am a BC student, family income 1.5 lakh, B.Tech at a government college',
    persona_hi: 'मैं SC छात्र हूँ, परिवार की आय 2 लाख, सरकारी कॉलेज में B.Tech',
    persona_te: 'నేను BC విద్యార్థిని, కుటుంబ ఆదాయం 1.5 లక్షలు, ప్రభుత్వ కళాశాలలో B.Tech',
    placeholder_input: 'यहाँ लिखें… जैसे मैं 19 साल का हूँ, SC, BCom',
    btn_send: 'भेजें',
    btn_mic: '🎙 बोलें',
    mic_listening: 'सुन रहे हैं…',
    blocked_aadhaar: '⚠️ आधार कभी साझा न करें — हमने उसे हटा दिया है।',
    q_category: 'आप किस वर्ग में आते हैं?',
    q_family_income: 'परिवार की वार्षिक आय लगभग कितनी है?',
    q_education_level: 'आप अभी क्या पढ़ रहे हैं?',
    q_gender: 'आपका लिंग क्या है?',
    q_institution_type: 'आप किस प्रकार के संस्थान में पढ़ते हैं?',
    btn_skip: 'छोड़ें',
    cat_SC: 'SC', cat_ST: 'ST', cat_BC: 'BC', cat_EWS: 'EWS', cat_Minority: 'अल्पसंख्यक', cat_OC: 'OC',
    inc_80000: '₹1 लाख से कम', inc_200000: '₹1–3 लाख', inc_500000: '₹3–8 लाख', inc_1000000: '₹8 लाख से अधिक',
    edu_class_8: 'कक्षा 8', edu_class_9_10: 'कक्षा 9–10', edu_intermediate: 'इंटरमीडिएट (11–12)',
    edu_diploma: 'डिप्लोमा', edu_ug: 'स्नातक (UG)', edu_pg: 'स्नातकोत्तर', edu_phd: 'पीएचडी',
    gen_male: 'पुरुष', gen_female: 'महिला',
    inst_government: 'सरकारी', inst_aided: 'सहायता प्राप्त', inst_private: 'निजी',
    thinking: 'सोच रहे हैं…',
    profile_title: 'हमने जो समझा',
    edit_chip_prefix: 'बदलें',
    recheck_btn: 'उत्तर दें और दोबारा जाँचें',
    f_age: 'आयु', f_gender: 'लिंग', f_category: 'वर्ग', f_family_income: 'पारिवारिक आय',
    f_education_level: 'शिक्षा स्तर', f_course: 'कोर्स', f_institution_type: 'संस्थान का प्रकार',
    f_state: 'राज्य', f_disability: 'दिव्यांगता', f_marks_percent: 'अंक %',
    not_given: 'नहीं दिया', yes: 'हाँ', no: 'नहीं',
    results_title: 'आपके परिणाम',
    summary_full: 'आप {x} योजनाओं के लिए पात्र हो सकते हैं · अगली अंतिम तिथि {n} दिन में · संभावित, निश्चित नहीं',
    summary_nodl: 'आप {x} योजनाओं के लिए पात्र हो सकते हैं · संभावित, निश्चित नहीं',
    btn_read: '🔊 ज़ोर से पढ़ें',
    btn_stop_read: '⏹ पढ़ना रोकें',
    btn_whatsapp: '📲 WhatsApp पर साझा करें',
    btn_print: '🖨️ चेकलिस्ट प्रिंट करें',
    wa_intro: 'वे योजनाएँ जिनके लिए मैं पात्र हो सकता हूँ',
    wa_tagline: 'निःशुल्क, कोई बिचौलिया नहीं, संभावित है निश्चित नहीं',
    print_title: 'इसे अपने कॉलेज / मीसेवा केंद्र ले जाएँ',
    status_likely: 'संभावित', status_maybe: 'शायद', status_no: 'नहीं',
    likely_title: '✅ संभावित रूप से पात्र', maybe_title: '❓ शायद', no_title: 'मेल नहीं खाती',
    no_results: 'अभी तक कोई योजना मेल नहीं खाई। अपने बारे में और बताएँ।',
    rule_pass: '✅ पास', rule_fail: '❌ फेल', rule_unknown: '❓ अज्ञात',
    rule_line: '{label} · आप: {value}',
    level_label: 'स्तर', provider_label: 'प्रदाता', benefit_label: 'लाभ',
    btn_official: 'आधिकारिक वेबसाइट', last_verified: 'अंतिम सत्यापन', not_verified: 'सत्यापित नहीं',
    deadline_none: 'कोई आगामी अंतिम तिथि नहीं', days_left: '{n} दिन बाकी', days_red: 'जल्द समाप्त',
    docs_title: 'तैयार रखने वाले दस्तावेज़', steps_title: 'चरण',
    btn_track: 'इसे ट्रैक करें', btn_tracked: 'ट्रैक किया ✓', btn_calendar: 'कैलेंडर में जोड़ें',
    whatif_title: '💡 यह क्या बदल सकता है',
    needs_line: 'ज़रूरत: {label}', tellus_line: 'हमें बताएँ: {label}',
    err_prefix: 'कुछ गलत हो गया: ', err_network: 'सर्वर से संपर्क नहीं हो सका। कृपया फिर कोशिश करें।',
    apps_title: 'मेरे आवेदन', apps_sub: 'देखें हर योजना कहाँ तक पहुँची',
    apps_empty: 'अभी कोई आवेदन ट्रैक नहीं हुआ। कोई योजना खोलकर "इसे ट्रैक करें" दबाएँ।',
    apps_stage: 'आवेदन की स्थिति',
    stat_not_started: 'शुरू नहीं हुआ', stat_documents_ready: 'दस्तावेज़ तैयार', stat_applied: 'आवेदन किया',
    stat_under_verification: 'सत्यापन में', stat_received: 'प्राप्त हुआ',
    footer_note: 'निःशुल्क सेवा। बिचौलिए की ज़रूरत नहीं। अंतिम निर्णय योजना के प्राधिकरण का है।',
    btn_how: 'हम कैसे तय करते हैं',
    modal_title: 'हम कैसे तय करते हैं',
    modal_1: 'AI केवल आपके शब्द समझता है। वह उन्हें उम्र और वर्ग जैसे फ़ील्ड में बदलता है।',
    modal_2: 'तय नियम पात्रता तय करते हैं। एक ही उत्तर से हमेशा एक ही परिणाम आता है।',
    modal_3: 'आधिकारिक स्रोत, हर एक की सत्यापन तिथि और आधिकारिक लिंक के साथ।',
    modal_4: 'हम आधार कभी संग्रहित नहीं करते। कोई भी आधार नंबर सबसे पहले हटा दिया जाता है।',
    btn_close: 'बंद करें',
    link_partners: 'साझेदारों के लिए',
    sec_likely_label: 'संभावित रूप से पात्र', sec_maybe_label: 'शायद', sec_no_label: 'मेल नहीं खाती'
  },
  te: {
    app_name: 'HaqDar',
    page_title: 'HaqDar · స్కాలర్‌షిప్‌లు',
    trust_line: '{n} పథకాలు · {date} న ధృవీకరించబడింది · ఆధార్ నిల్వ లేదు · ఉచితం',
    lang_title: 'మీ భాషను ఎంచుకోండి',
    lang_label: 'భాష',
    lang_name_en: 'English',
    lang_name_hi: 'हिन्दी',
    lang_name_te: 'తెలుగు',
    tab_chat: 'వెతకండి',
    tab_results: 'ఫలితాలు',
    tab_apps: 'నా దరఖాస్తులు',
    opening_msg: 'మీ గురించి చెప్పండి — చదువు, కుటుంబ ఆదాయం, కేటగిరీ. టైప్ చేయండి లేదా మైక్ నొక్కండి.',
    persona_en: 'I am a BC student, family income 1.5 lakh, B.Tech at a government college',
    persona_hi: 'मैं SC छात्र हूँ, परिवार की आय 2 लाख, सरकारी कॉलेज में B.Tech',
    persona_te: 'నేను BC విద్యార్థిని, కుటుంబ ఆదాయం 1.5 లక్షలు, ప్రభుత్వ కళాశాలలో B.Tech',
    placeholder_input: 'ఇక్కడ టైప్ చేయండి… ఉదా: నా వయస్సు 19, SC, బీకామ్',
    btn_send: 'పంపండి',
    btn_mic: '🎙 మాట్లాడండి',
    mic_listening: 'వింటోంది…',
    blocked_aadhaar: '⚠️ ఆధార్ ఎప్పుడూ షేర్ చేయవద్దు — మేము దానిని తీసివేశాము.',
    q_category: 'మీ కేటగిరీ ఏమిటి?',
    q_family_income: 'కుటుంబ వార్షిక ఆదాయం సుమారు ఎంత?',
    q_education_level: 'మీరు ఇప్పుడు ఏమి చదువుతున్నారు?',
    q_gender: 'మీ లింగం ఏమిటి?',
    q_institution_type: 'మీరు ఏ రకమైన సంస్థలో చదువుతున్నారు?',
    btn_skip: 'దాటవేయి',
    cat_SC: 'SC', cat_ST: 'ST', cat_BC: 'BC', cat_EWS: 'EWS', cat_Minority: 'మైనారిటీ', cat_OC: 'OC',
    inc_80000: '₹1 లక్ష లోపు', inc_200000: '₹1–3 లక్షలు', inc_500000: '₹3–8 లక్షలు', inc_1000000: '₹8 లక్షలకు పైగా',
    edu_class_8: '8వ తరగతి', edu_class_9_10: '9–10 తరగతి', edu_intermediate: 'ఇంటర్మీడియట్ (11–12)',
    edu_diploma: 'డిప్లొమా', edu_ug: 'డిగ్రీ (UG)', edu_pg: 'పీజీ', edu_phd: 'పీహెచ్‌డీ',
    gen_male: 'పురుషుడు', gen_female: 'స్త్రీ',
    inst_government: 'ప్రభుత్వ', inst_aided: 'ఎయిడెడ్', inst_private: 'ప్రైవేట్',
    thinking: 'ఆలోచిస్తోంది…',
    profile_title: 'మేము అర్థం చేసుకున్నది',
    edit_chip_prefix: 'మార్చండి',
    recheck_btn: 'జవాబు ఇచ్చి మళ్ళీ తనిఖీ చేయి',
    f_age: 'వయస్సు', f_gender: 'లింగం', f_category: 'కేటగిరీ', f_family_income: 'కుటుంబ ఆదాయం',
    f_education_level: 'విద్యా స్థాయి', f_course: 'కోర్సు', f_institution_type: 'సంస్థ రకం',
    f_state: 'రాష్ట్రం', f_disability: 'దివ్యాంగత', f_marks_percent: 'మార్కులు %',
    not_given: 'ఇవ్వలేదు', yes: 'అవును', no: 'కాదు',
    results_title: 'మీ ఫలితాలు',
    summary_full: 'మీరు {x} పథకాలకు అర్హులు కావచ్చు · తదుపరి గడువు {n} రోజుల్లో · అవకాశం, హామీ కాదు',
    summary_nodl: 'మీరు {x} పథకాలకు అర్హులు కావచ్చు · అవకాశం, హామీ కాదు',
    btn_read: '🔊 బిగ్గరగా చదవండి',
    btn_stop_read: '⏹ చదవడం ఆపండి',
    btn_whatsapp: '📲 WhatsApp లో షేర్ చేయండి',
    btn_print: '🖨️ చెక్‌లిస్ట్ ప్రింట్ చేయండి',
    wa_intro: 'నేను అర్హత కలిగి ఉండవచ్చని అనుకుంటున్న పథకాలు',
    wa_tagline: 'ఉచితం, మధ్యవర్తులు లేరు, అవకాశం మాత్రమే, హామీ కాదు',
    print_title: 'దీన్ని మీ కాలేజీ / మీసేవా కేంద్రానికి తీసుకెళ్లండి',
    status_likely: 'అవకాశం ఉంది', status_maybe: 'బహుశా', status_no: 'కాదు',
    likely_title: '✅ అర్హత అవకాశం ఉంది', maybe_title: '❓ బహుశా', no_title: 'సరిపోలలేదు',
    no_results: 'ఇంకా ఏ పథకమూ సరిపోలలేదు. మీ గురించి మరికొంత చెప్పండి.',
    rule_pass: '✅ పాస్', rule_fail: '❌ ఫెయిల్', rule_unknown: '❓ తెలియదు',
    rule_line: '{label} · మీరు: {value}',
    level_label: 'స్థాయి', provider_label: 'అందించేవారు', benefit_label: 'ప్రయోజనం',
    btn_official: 'అధికారిక వెబ్‌సైట్', last_verified: 'చివరిసారి ధృవీకరించినది', not_verified: 'ధృవీకరించలేదు',
    deadline_none: 'రాబోయే గడువు లేదు', days_left: '{n} రోజులు మిగిలి ఉన్నాయి', days_red: 'త్వరలో ముగుస్తుంది',
    docs_title: 'సిద్ధంగా ఉంచుకోవాల్సిన పత్రాలు', steps_title: 'దశలు',
    btn_track: 'ట్రాక్ చేయి', btn_tracked: 'ట్రాక్ చేయబడింది ✓', btn_calendar: 'క్యాలెండర్‌కు జోడించు',
    whatif_title: '💡 దీన్ని ఏది మార్చగలదు',
    needs_line: 'అవసరం: {label}', tellus_line: 'మాకు చెప్పండి: {label}',
    err_prefix: 'ఏదో తప్పు జరిగింది: ', err_network: 'సర్వర్‌ను చేరలేకపోయాం. దయచేసి మళ్ళీ ప్రయత్నించండి.',
    apps_title: 'నా దరఖాస్తులు', apps_sub: 'ప్రతి పథకం ఎక్కడ ఉందో చూడండి',
    apps_empty: 'ఇంకా ఏ దరఖాస్తూ ట్రాక్ కాలేదు. ఒక పథకం తెరిచి "ట్రాక్ చేయి" నొక్కండి.',
    apps_stage: 'దరఖాస్తు స్థితి',
    stat_not_started: 'ప్రారంభించలేదు', stat_documents_ready: 'పత్రాలు సిద్ధం', stat_applied: 'దరఖాస్తు చేశారు',
    stat_under_verification: 'ధృవీకరణలో ఉంది', stat_received: 'అందింది',
    footer_note: 'ఇది ఉచిత సేవ. మధ్యవర్తులు అవసరం లేదు. తుది నిర్ణయం పథక అధికారిదే.',
    btn_how: 'మేము ఎలా నిర్ణయిస్తాం',
    modal_title: 'మేము ఎలా నిర్ణయిస్తాం',
    modal_1: 'AI మీ మాటలను మాత్రమే అర్థం చేసుకుంటుంది. వాటిని వయస్సు, కేటగిరీ వంటి ఫీల్డ్‌లుగా మారుస్తుంది.',
    modal_2: 'అర్హతను స్థిరమైన, మార్చగల నియమాలే నిర్ణయిస్తాయి. ఒకే జవాబులకు ఎల్లప్పుడూ ఒకే ఫలితం.',
    modal_3: 'అధికారిక మూలాలు, ప్రతిదానికీ ధృవీకరించిన తేదీ మరియు అధికారిక లింక్‌తో.',
    modal_4: 'ఆధార్‌ను మేము ఎప్పుడూ నిల్వ చేయము. ఏ ఆధార్ నంబర్ అయినా ముందే తీసివేయబడుతుంది.',
    btn_close: 'మూసివేయి',
    link_partners: 'భాగస్వాముల కోసం',
    sec_likely_label: 'అర్హత అవకాశం ఉంది', sec_maybe_label: 'బహుశా', sec_no_label: 'సరిపోలలేదు'
  }
};

var LANG_KEY = 'haqdar_lang';
var LANG_CODES = ['en', 'hi', 'te'];
var SPEECH_LOCALE = { en: 'en-IN', hi: 'hi-IN', te: 'te-IN' };
var _lang = null;

/** True when the user has already picked a language (controls the first-screen picker). */
function hasSavedLang() {
  try {
    return LANG_CODES.indexOf(localStorage.getItem(LANG_KEY)) >= 0;
  } catch (e) {
    return false;
  }
}

/** Current language code; defaults to 'en' when nothing is saved. */
function getLang() {
  if (_lang) return _lang;
  var saved = null;
  try { saved = localStorage.getItem(LANG_KEY); } catch (e) { saved = null; }
  _lang = LANG_CODES.indexOf(saved) >= 0 ? saved : 'en';
  return _lang;
}

/** Saves and applies a language code. Unknown codes fall back to 'en'. */
function setLang(code) {
  _lang = LANG_CODES.indexOf(code) >= 0 ? code : 'en';
  try { localStorage.setItem(LANG_KEY, _lang); } catch (e) { /* storage blocked: keep in-memory value */ }
  document.documentElement.lang = _lang;
  return _lang;
}

/** Returns the string for key in the current language (falls back to en), with {name} placeholders filled. */
function t(key, vars) {
  var lang = getLang();
  var s = (T[lang] && T[lang][key]) || T.en[key] || key;
  if (vars) {
    for (var k in vars) {
      s = s.split('{' + k + '}').join(String(vars[k]));
    }
  }
  return s;
}

/** Speech locale for the current language (en-IN / hi-IN / te-IN). */
function speechLocale() {
  return SPEECH_LOCALE[getLang()] || 'en-IN';
}

/** Fills [data-i18n] text, [data-i18n-placeholder] placeholders and [data-lang-name] option/button labels. */
function applyI18n(root) {
  root = root || document;
  document.title = t('page_title');
  root.querySelectorAll('[data-i18n]').forEach(function (el) {
    el.textContent = t(el.getAttribute('data-i18n'));
  });
  root.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
    el.setAttribute('placeholder', t(el.getAttribute('data-i18n-placeholder')));
  });
  root.querySelectorAll('[data-lang-name]').forEach(function (el) {
    el.textContent = t('lang_name_' + el.getAttribute('data-lang-name'));
  });
  document.documentElement.lang = getLang();
}
